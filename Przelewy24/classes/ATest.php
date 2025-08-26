<?php namespace EC\Przelewy24;
defined('_ESPADA') or die(NO_ACCESS);

use E, EC,
    EC\Api\CArgs, EC\Api\CResult;

class ATest extends EC\AApi {
    protected ?EC\MDatabase $db = null;

    public function __construct(EC\SApi $site) {
        parent::__construct($site);

        $site->addM('db', new EC\MDatabase());

        $this->db = $site->m->db;

        $this->action('pay', 'action_Pay', [
            'token' => true,
        ]);
    }

    public function action_Pay(CArgs $args) {
        $rTransaction = (new TTransactions($this->db))->row_Where([
            [ 'Token', '=', $args->token ],
        ]);
        if ($rTransaction === null)
            return CResult::Failure('Transaction does not exist.');

        $rTest = (new TTests($this->db))->row_Where([
            [ 'Id', '=', $rTransaction['Id'] ],
        ]);
        if ($rTest === null)
            return CResult::Failure('Test info does not exist.');

        $req = new EC\CHttpRequest();

        $data = [
            'merchantId' => $rTest['Info']['merchantId'],
            'posId' => $rTest['Info']['posId'],
            'sessionId' => $rTest['Info']['sessionId'],
            'amount' => $rTest['Info']['amount'],
            'originAmount' => $rTest['Info']['amount'],
            'currency' => $rTest['Info']['currency'],
            'orderId' => $rTransaction['Id'],
            'methodId' => 1,
            'statement' => 'Opłata',
        ];
        $data['sign'] = HPrzelewy24::GetSign_Notification(EC\HConfig::GetRequired(
                'Przelewy24', 'testCRC'), $data);

        $res = $req->post_JSON($rTest['Info']['urlStatus'], $data);

        $json = HPrzelewy24::ParseResponse($res, $resError);
        if ($resError !== null) {
            return CResult::Failure()
                ->debug($resError);
        }

        return CResult::Success()
            ->add('urlReturn', $rTest['Info']['urlReturn']);
    }
}