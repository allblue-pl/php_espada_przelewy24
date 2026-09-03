<?php namespace EC\Przelewy24\_Tables;
defined('_ESPADA') or die(NO_ACCESS);

use E, EC;
use EC\ABData\HABTablesHelper;
use EC\Database;
use EC\Database\MDatabase;
use EC\Database\TTable;
use Override;

/**
 *
 * @phpstan-type _T_TRPrzelewy24_Transactions array{
 *     Id: int|null,
 *     MerchantId: int,
 *     PosId: int,
 *     Amount: float,
 *     Currency: string,
 *     Token: string|null,
 *     Result: string|null,
 *     Paid: bool,
 *     Expires: float|null,
 * }
 */
class _TTransactions extends TTable {
    /**
     *
     * @param _T_TRPrzelewy24_Transactions $row
     * @return _T_TRPrzelewy24_Transactions
     */
    static public function AssertRow(array $row): array {
        return $row;
    }

    /**
     *
     * @param list<_T_TRPrzelewy24_Transactions> $rows
     * @return list<_T_TRPrzelewy24_Transactions>
     */
    static public function AssertRows(array $rows): array {
        return $rows;
    }

    // /**
    //  *
    //  * @param array|null $row
    //  * @return _T_TRPrzelewy24_Transactions|null
    //  */
    // static public function CastRow(array|null $row): array|null {
    //     /* phpstan-ignore return.type */
    //     return $row;
    // }

    // /**
    //  *
    //  * @param array $rows
    //  * @return list<_T_TRPrzelewy24_Transactions>
    //  */
    // static public function CastRows(array $rows): array {
    //     return $rows;
    // }


    public function __construct(MDatabase $db, $tablePrefix = 'p24_t') {
        parent::__construct($db, 'Przelewy24_Transactions', $tablePrefix);

        $this->setColumns([
            'Id' => new Database\FInt(true, true), 
            'MerchantId' => new Database\FInt(true, false), 
            'PosId' => new Database\FInt(true, false), 
            'Amount' => new Database\FFloat(true), 
            'Currency' => new Database\FString(true, 4), 
            'Token' => new Database\FString(false, 128), 
            'Result' => new Database\FText(false, 'medium'), 
            'Paid' => new Database\FBool(true), 
            'Expires' => new Database\FLong(false), 
        ]);
        $this->setPKs([ 'Id' ]);


        HABTablesHelper::SetTableVFields($this);
    }

    /** 
     * @return _T_TRPrzelewy24_Transactions|null
     */
     #[Override]
    public function row_ByColumn(string $colName, mixed $colValue, 
            string $groupExtension = '', bool $forUpdate = false): array|null {
        /* @phpstan-ignore return.type */
        return parent::row_ByColumn($colName, $colValue, $groupExtension, $forUpdate);
    }

    /** 
     * @return _T_TRPrzelewy24_Transactions|null
     */
    #[Override]
    public function row_ByPKs(array $keys, string $groupExtension = '', 
            bool $forUpdate = false): array|null {
        /* @phpstan-ignore return.type */
        return parent::row_ByPKs($keys, $groupExtension, $forUpdate);
    }

    /** 
     * @return _T_TRPrzelewy24_Transactions|null
     */
    #[Override]
    public function row_Where(array $conditions = [], string $groupExtension = '',
            bool $forUpdate = false): array|null {
        /* @phpstan-ignore return.type */
        return parent::row_Where($conditions, $groupExtension, $forUpdate);
    }

    /** 
     * @return list<_T_TRPrzelewy24_Transactions>
     */
    #[Override]
    public function select_ByPKs(array $pks, string $groupExtension = ''): array {
        return parent::select_ByPKs($pks, $groupExtension);
    }

    /** 
     * @return list<_T_TRPrzelewy24_Transactions>
     */
    #[Override]
    public function select_Where(array $conditions = [], string $groupExtension = '',
            bool $tableOnly = false): array {
        return parent::select_Where($conditions, $groupExtension);
    }

    /** 
     * @return _T_TRPrzelewy24_Transactions
     */
    #[Override]
    public function stripRow_TableColumnsOnly(array $row): array {
        /* @phpstan-ignore return.type */
        return parent::stripRow_TableColumnsOnly($row);
    }
}
