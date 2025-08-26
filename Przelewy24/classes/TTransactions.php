<?php namespace EC\Przelewy24;
defined('_ESPADA') or die(NO_ACCESS);

use E, EC,
    EC\Database;

class TTransactions extends _TTransactions {
    public function __construct(EC\MDatabase $db) {
        parent::__construct($db, 'p24_tr');

        $this->setColumnParser('Result', [
            'out' => function($row, $name, $value) {
                if ($value === null) {
                    return [
                        $name => null,
                    ];
                }

                return [
                    $name => json_decode($value, true)['data']
                ];
            },
            'in' => function($row, $name, $value) {
                if ($value === null)
                    return null;

                return json_encode([ 'data' => $value ]);
            }
        ]);
    }
}
