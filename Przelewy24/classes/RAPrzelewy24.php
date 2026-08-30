<?php namespace EC\Przelewy24;
defined('_ESPADA') or die(NO_ACCESS);

use E, EC;
use EC\RestApi\CResult;
use EC\Database\MDatabase;
use EC\Date\HDate;
use EC\HttpRequest\CHttpRequest;
use EC\Log\HLog;
use EC\RestApi\ARestApi;
use EC\RestApi\SRestApi;

class RAPrzelewy24 extends ARestApi {
    // static private $AllowedIPs = [];
    protected MDatabase $db;

    public function __construct(SRestApi $site) {
        parent::__construct($site);

        $this->db = $site->getDB();

        $this->action_POST('notification', 'action_POST_Notification');

        HDate::SetTimeZone('Europe/Warsaw');

        E\Exception::AddOnErrorListener(function($e) {
            if ($this->db->isConnected())
                $this->db->transaction_Finish(false);
            // if (!EDEBUG) {
                HLog::Add($this->db, null,
                        'Przelewy24\\RAPrzelewy24 Error.', [
                    'message' => $e->getMessage(),
                    'backtrace' => $e->getTrace(),
                ]);
            // }
        });
    }

    public function action_POST_Notification(array $uriArgs, array $apiArgs) {
        $this->db->transaction_Start();

        $transactionId = $apiArgs['sessionId'];
        $rTransaction = (new TTransactions($this->db))->row_Where([
            [ 'Id', '=', $transactionId ],
        ], '', true);
        if ($rTransaction === null) {
            $this->db->transaction_Finish(false);
            HLog::Add($this->db, null, 
                    "RAPrzelewy24:POST_Notification -> " . 
                    "Transaction does not exist.");
            return CResult::Error(400, [
                'error' => 'Transaction does not exist.',
            ]);
        }

        $rTransactionSecret = (new TTransactionSecrets($this->db))->row_Where([
            [ 'Id', '=', $transactionId ],
        ]);
        if ($rTransactionSecret === null) {
            $this->db->transaction_Finish(false);
            HLog::Add($this->db, null, 
                    "RAPrzelewy24:POST_Notification -> " . 
                    "Transaction secret does not exist.");
            return CResult::Error(400, [
                'error' => 'Transaction secret does not exist.',
            ]);
        }

        $sign_Notification = HPrzelewy24::GetSign_Notification(
                $rTransactionSecret['CRC'], $apiArgs);
            
        if ($sign_Notification !== $apiArgs['sign']) {
            $this->db->transaction_Finish(false);
            HLog::Add($this->db, null, 
                    "RAPrzelewy24:POST_Notification -> " . 
                    "Signs do not match.");
            return CResult::Error(400, [
                'error' => 'Invalid sign.',
            ]);
        }

        $req = new CHttpRequest();
        $req->setAuth(strval($rTransaction['PosId']), $rTransactionSecret['Secret']);

        $data = [
            "merchantId" => $rTransaction['MerchantId'],
            "posId" => $rTransaction['PosId'],
            "sessionId" => strval($rTransaction['Id']),
            "amount" => $rTransaction['Amount'],
            "currency" => $rTransaction['Currency'],
            "orderId" => $apiArgs['orderId'],
        ];
        $data['sign'] = HPrzelewy24::GetSign_Verification($rTransactionSecret['CRC'], 
                $data);
        $res = $req->put_JSON(HPrzelewy24::GetUri_Api() . 'transaction/verify', 
                $data);

        $json = HPrzelewy24::ParseResponse($res, $resError);
        if ($resError !== null) {
            $this->db->transaction_Finish(false);
            HLog::Add($this->db, null, 
                    "RAPrzelewy24:POST_Notification -> " . 
                    "Verify response error: {$resError}");
            return CResult::Error(400, [
                'error' => $resError,
            ]);
        }

        $paid = $apiArgs['amount'] >= $rTransaction['Amount'];

        if (!(new TTransactions($this->db))->update([[
            'Id' => $rTransaction['Id'],
            'Result' => $apiArgs,
            'Paid' => $paid,
                ]])) {
            $this->db->transaction_Finish(false);
            HLog::Add($this->db, null, 
                    "RAPrzelewy24:POST_Notification -> " . 
                    "Cannot update transaction.");
            return CResult::Error(400, [
                'error' => 'Cannot update transaction.',
            ]);
        }

        if (!$this->db->transaction_Finish(true)) {
            HLog::Add($this->db, null, 
                    "RAPrzelewy24:POST_Notification -> " . 
                    "Cannot commit.");
            return CResult::Error(400, [
                'error' => 'Cannot commit.',
            ]);
        }

        return CResult::Success();
    }
}