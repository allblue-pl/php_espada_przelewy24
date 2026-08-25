<?php namespace EC\Przelewy24\_Tables;
defined('_ESPADA') or die(NO_ACCESS);

use E, EC;
use EC\Database;
use EC\Database\MDatabase;
use EC\Database\TTable;

/**
 *
 * @phpstan-type _T_RPrzelewy24_Transactions array{
 *     Id: int,
 *     MerchantId: int,
 *     PosId: int,
 *     Amount: float,
 *     Currency: string,
 *     Token: string,
 *     Result: string|null,
 *     Paid: bool,
 *     Expires: float|null,
 * }
 */
class _TTransactions extends TTable {
    /**
     *
     * @param array $row
     * @return _T_RPrzelewy24_Transactions
     */
    static public function AssertRow(array $row): array {
        /* @phpstan-ignore return.type */
        return $row;
    }

    /**
     *
     * @param array $rows
     * @return array<_T_RPrzelewy24_Transactions>
     */
    static public function AssertRows(array $rows): array {
        return $rows;
    }


    public function __construct(MDatabase $db, $tablePrefix = 'p24_t') {
        parent::__construct($db, 'Przelewy24_Transactions', $tablePrefix);

        $this->setColumns([
            'Id' => new Database\FInt(true, false), 
            'MerchantId' => new Database\FInt(true, false), 
            'PosId' => new Database\FInt(true, false), 
            'Amount' => new Database\FFloat(true), 
            'Currency' => new Database\FString(true, 4), 
            'Token' => new Database\FString(true, 128), 
            'Result' => new Database\FText(false, 'medium'), 
            'Paid' => new Database\FBool(true), 
            'Expires' => new Database\FLong(false), 
        ]);
        $this->setPKs([ 'Id' ]);

    }
}
