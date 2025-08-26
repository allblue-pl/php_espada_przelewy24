<?php namespace EC\Przelewy24;
defined('_ESPADA') or die(NO_ACCESS);

use E, EC,
    EC\Database;

class _TTransactionSecrets extends Database\TTable {
    public function __construct(EC\MDatabase $db, $tablePrefix = 't') {
        parent::__construct($db, 'Przelewy24_TransactionSecrets', $tablePrefix);

        $this->setColumns([
            'Id' => new Database\FInt(true),
            'Secret' => new Database\FString(true, 128),
            'CRC' => new Database\FString(true, 128),
        ]);
    }
}
