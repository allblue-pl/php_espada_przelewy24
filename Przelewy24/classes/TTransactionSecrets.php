<?php namespace EC\Przelewy24;
defined('_ESPADA') or die(NO_ACCESS);

use E, EC;
use EC\Database\MDatabase;
use EC\Przelewy24\_Tables\_TTransactionSecrets;

class TTransactionSecrets extends _TTransactionSecrets {
    public function __construct(MDatabase $db) {
        parent::__construct($db, 'p24_trs');
    }
}
