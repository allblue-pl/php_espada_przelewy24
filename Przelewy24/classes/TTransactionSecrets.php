<?php namespace EC\Przelewy24;
defined('_ESPADA') or die(NO_ACCESS);

use E, EC,
    EC\Database;

class TTransactionSecrets extends _TTransactionSecrets {
    public function __construct(EC\MDatabase $db) {
        parent::__construct($db, 'p24_trs');
    }
}
