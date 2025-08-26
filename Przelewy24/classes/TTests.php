<?php namespace EC\Przelewy24;
defined('_ESPADA') or die(NO_ACCESS);

use E, EC,
    EC\Database;

class TTests extends _TTests {
    public function __construct(EC\MDatabase $db) {
        parent::__construct($db, 'p24_ts');

        $this->setColumnParser('Info', [
            'out' => function($row, $name, $value) {
                if ($value === null) {
                    return [ $name => null ];
                };

                return [
                    $name => json_decode($value, true)['data']
                ];
            },
            'in' => function($row, $name, $value) {
                return json_encode([ 'data' => $value ]);
            }
        ]);
    }
}
