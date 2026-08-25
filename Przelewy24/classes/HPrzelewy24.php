<?php namespace EC\Przelewy24;
defined('_ESPADA') or die(NO_ACCESS);

use E, EC;
use EC\Config\HConfig;
use EC\Database\MDatabase;
use EC\Date\HDate;
use EC\HttpRequest\CHttpRequest;

/**
 * @phpstan-type TransactionInfo array{
 *     posId: int, 
 *     secret: string, 
 *     crc: string, 
 *     merchantId: int,
 *     amount: float, 
 *     currency: string, 
 *     description: string , 
 *     email: string , 
 *     name: string , 
 *     label: string , 
 *     urlReturn: string, 
 *     urlStatus: string, 
 *     timeLimit: int,
 * }
 */

class HPrzelewy24 {
    static public function CreateTestPages(E\SitePages $eSite, array $langs) {
        if (E\Config::IsType('przelewy24_local')) {
            $page = $eSite->page('przelewy24Test.restApi.transaction', 'RestApi:restApi', 
                    [ 'restApi' => 'EC\Przelewy24\RATest_Transaction' ]);
            foreach ($langs as $lang => $langInfo)
                $page->alias($lang, "przelewy24-test/transaction/*");

            $page = $eSite->page('przelewy24Test.api', 'Api:api', 
                    [ 'api' => 'EC\Przelewy24\ATest' ]);
            foreach ($langs as $lang => $langInfo)
                $page->alias($lang, "przelewy24-test/*");
        }
    }

    /**
     * @param TransactionInfo $transactionInfo 
     */
    static public function CreateTransaction(MDatabase $db, array $transactionInfo,
            ?string &$error = 'Unknown Error'): ?array {
        $db->requireTransaction();

        $tTransactions = new TTransactions($db);
        $rTransaction = [
            'Id' => null,
            'MerchantId' => $transactionInfo["merchantId"],
            'PosId' => $transactionInfo["posId"],
            'Amount' => $transactionInfo["amount"],
            'Currency' => $transactionInfo["currency"],
            'Token' => null,
            'Result' => null,
            'Paid' => false,
            'Expires' => E\Config::IsType('dev') ? HDate::GetTime() + 
                    HDate::Span_Minute * 1 : HDate::GetTime() + 
                    HDate::Span_Minute * $transactionInfo["timeLimit"],
        ];
        if (!$tTransactions->update([ $rTransaction ])) {
            $error = 'Cannot update transactions.';
            return null;
        }
        $rTransaction['Id'] = $tTransactions->getLastInsertedId();

        if (!(new TTransactionSecrets($db))->update([[
                'Id' => $rTransaction['Id'],
                'Secret' => $transactionInfo["secret"],
                'CRC' => $transactionInfo["crc"],
                    ]])) {
            if (!$tTransactions->update([ $rTransaction ])) {
                $error = 'Cannot update transaction secrets.';
                return null;
            }
        }

        /* Test */
        // $req = new EC\CHttpRequest();

        // $req->setAuth($posId, $secret);

        // $res = $req->get_JSON(self::GetUri_Api() . 'testAccess', []); 
        
        // $json = self::ParseResponse($res, $resError);
        // if ($resError !== null) {
        //     $db->transaction_Finish(false);
        //     $error = $resError;
        //     return null;
        // }
        /* / Test */

        $req = new CHttpRequest();

        $req->setAuth(strval($transactionInfo["posId"]), $transactionInfo["secret"]);

        $data = [
            "merchantId" => $transactionInfo["posId"],
            "posId" => $transactionInfo["posId"],
            "sessionId" => (string)$rTransaction['Id'],
            "amount" => $transactionInfo["amount"],
            "currency" => 'PLN',
            "description" => $transactionInfo["description"],
            "email" => $transactionInfo["email"],
            "client" => $transactionInfo["name"],
            "country" => "PL",
            "language" => E\Langs::Get('alias'),
            "urlReturn" => $transactionInfo["urlReturn"],
            "urlStatus" => $transactionInfo["urlStatus"],
            "timeLimit" => $transactionInfo["timeLimit"],
            "channel" => 1 + 2 + 16 + 4096 + 8192,
            "waitForResult" => true,
            "regulationAccept" => false,
            "shipping" => 0,
            "transferLabel" => $transactionInfo["label"],
            "encoding" => "UTF-8",
        ];
        $data['sign'] = self::GetSign_Transaction($transactionInfo["crc"], $data);
        $res = $req->post_JSON(self::GetUri_Api() . 'transaction/register', 
                $data);

        $json = self::ParseResponse($res, $resError);
        if ($resError !== null) {
            $error = $resError;
            return null;
        }

        $rTransaction['Token'] =  $json['data']['token'];
        if (!$tTransactions->update([[
            'Id' => $rTransaction['Id'],
            'Token' => $json['data']['token'],
                ]])) {
            $error = 'Cannot update transaction token.';
            return null;            
        }

        return $rTransaction;
    }

    static public function GetSign_Notification(string $crc, array $data) {
        $params = [
            'merchantId' => $data['merchantId'],
            'posId' => $data['posId'],
            'sessionId' => $data['sessionId'],
            'amount' => $data['amount'],
            'originAmount' => $data['amount'],
            'currency' => $data['currency'],
            'orderId' => $data['orderId'],
            'methodId' => $data['methodId'],
            'statement' => $data['statement'],
            'crc' => $crc,
        ];

        $combinedString = json_encode($params, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        
        return hash('sha384', $combinedString);
    }

    static public function GetSign_Transaction(string $crc, array $data) {
        $params = [
            'sessionId' => $data['sessionId'],
            'merchantId' => $data['merchantId'],
            'amount' => $data['amount'],
            'currency' => $data['currency'],
            'crc' => $crc,
        ];

        $combinedString = json_encode($params, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        return hash('sha384', $combinedString);
    }

    static public function GetSign_Verification(string $crc, array $data) {
        $params = [
            'sessionId' => $data['sessionId'],
            'orderId' => $data['orderId'],
            'amount' => $data['amount'],
            'currency' => $data['currency'],
            'crc' => $crc,
        ];

        $combinedString = json_encode($params, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        return hash('sha384', $combinedString);
    }

    static public function GetUri_Api() {    
        if (E\Config::IsType('przelewy24_local')) {
            return SITE_DOMAIN . SITE_BASE. E\Langs::Get()['alias'] . 
                    '/przelewy24-test/';
        }

        if (E\Config::IsType('przelewy24_dev'))
            return 'https://sandbox.przelewy24.pl/api/v1/';

        return 'https://secure.przelewy24.pl/api/v1/';
    }

    static public function GetUri_Transaction(string $token) {
        if (E\Config::IsType('przelewy24_local')) {
            return SITE_DOMAIN . HConfig::GetRequired('Przelewy24', 
                    'testUriBase') . "przelewy24-test/trnRequest/{$token}";
        }

        if (E\Config::IsType('przelewy24_dev'))
            return "https://sandbox.przelewy24.pl/trnRequest/{$token}";

        return "https://secure.przelewy24.pl/trnRequest/{$token}";
    }

    static function ParseResponse($response, ?string &$error): ?array {
        $statusCode = $response->getStatusCode();
        if ($statusCode !== 200) {
            $error = "Wrong response status code {$statusCode} -> " . 
                    $response->getBody();
            return null;
        }

        if ((string)$response->getBody() === '')
            return null;

        $json = json_decode($response->getBody(), true);
        if ($json === null) {
            $error = 'Cannot parse response json -> ' . $response->getBody();
            return null;
        };

        return $json;
    }
}