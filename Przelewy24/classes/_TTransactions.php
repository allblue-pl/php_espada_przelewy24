<?php namespace EC\Przelewy24;
defined('_ESPADA') or die(NO_ACCESS);

use E, EC,
    EC\Database;

class _TTransactions extends Database\TTable {
    public function __construct(EC\MDatabase $db, $tablePrefix = 't') {
        parent::__construct($db, 'Przelewy24_Transactions', $tablePrefix);

        $this->setColumns([
            'Id' => new Database\FInt(true),
            'MerchantId' => new Database\FInt(true),
            'PosId' => new Database\FInt(true, 16),
            'Amount' => new Database\FFloat(true),
            'Currency' => new Database\FString(true, 4),
            'Token' => new Database\FString(true, 128),
            'Result' => new Database\FText(false, 'medium'),
            'Paid' => new Database\FBool(true),
        ]);
    }
}
