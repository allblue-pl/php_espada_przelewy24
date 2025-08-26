<?php namespace EC\Przelewy24;
defined('_ESPADA') or die(NO_ACCESS);

use E, EC,
    EC\RestApi\CResult;

class RATest_Transaction extends EC\ARestApi {
    static private $AllowedIPs = [];

    protected ?EC\MDatabase $db = null;

    public function __construct(EC\SRestApi $site) {
        parent::__construct($site,);

        $site->addM('db', new EC\MDatabase());

        $this->db = $site->m->db;

        $this->action_POST('register', 'action_POST_Register');
        $this->action_PUT('verify', 'action_PUT_Verify');
    }

    public function action_POST_Register(array $uriArgs, array $apiArgs) {
        if (!(new TTests($this->db))->update([[
            'Id' => $apiArgs['sessionId'],
            'Info' => $apiArgs, 
                ]])) {
            return CResult::Error(400, [
                'error' => 'Cannot update tests.',
            ]);
        }

        return CResult::Success([
            'data' => [
                'token' => "przelewy24-test_{$apiArgs['sessionId']}",
            ],
            'responseCode' => 0,
        ]);
    }

    public function action_PUT_Verify(array $uriArgs, array $apiArgs) {
        return CResult::Success([
            'data' => [
                'status' => 'success',
            ],
            'responseCode' => 0,
        ]);
    }
}