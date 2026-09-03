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
 * @phpstan-type _T_TRPrzelewy24_Tests array{
 *     Id: int,
 *     Info: mixed,
 * }
 */
class _TTests extends TTable {
    /**
     *
     * @param _T_TRPrzelewy24_Tests $row
     * @return _T_TRPrzelewy24_Tests
     */
    static public function AssertRow(array $row): array {
        return $row;
    }

    /**
     *
     * @param list<_T_TRPrzelewy24_Tests> $rows
     * @return list<_T_TRPrzelewy24_Tests>
     */
    static public function AssertRows(array $rows): array {
        return $rows;
    }

    // /**
    //  *
    //  * @param array|null $row
    //  * @return _T_TRPrzelewy24_Tests|null
    //  */
    // static public function CastRow(array|null $row): array|null {
    //     /* phpstan-ignore return.type */
    //     return $row;
    // }

    // /**
    //  *
    //  * @param array $rows
    //  * @return list<_T_TRPrzelewy24_Tests>
    //  */
    // static public function CastRows(array $rows): array {
    //     return $rows;
    // }


    public function __construct(MDatabase $db, $tablePrefix = 'p24_tst') {
        parent::__construct($db, 'Przelewy24_Tests', $tablePrefix);

        $this->setColumns([
            'Id' => new Database\FInt(true, false), 
            'Info' => new Database\FText(true, 'medium'), 
        ]);
        $this->setPKs([ 'Id' ]);

        $this->setColumnParser_JSON("Info");

        HABTablesHelper::SetTableVFields($this);
    }

    /** 
     * @return _T_TRPrzelewy24_Tests|null
     */
     #[Override]
    public function row_ByColumn(string $colName, mixed $colValue, 
            string $groupExtension = '', bool $forUpdate = false): array|null {
        /* @phpstan-ignore return.type */
        return parent::row_ByColumn($colName, $colValue, $groupExtension, $forUpdate);
    }

    /** 
     * @return _T_TRPrzelewy24_Tests|null
     */
    #[Override]
    public function row_ByPKs(array $keys, string $groupExtension = '', 
            bool $forUpdate = false): array|null {
        /* @phpstan-ignore return.type */
        return parent::row_ByPKs($keys, $groupExtension, $forUpdate);
    }

    /** 
     * @return _T_TRPrzelewy24_Tests|null
     */
    #[Override]
    public function row_Where(array $conditions = [], string $groupExtension = '',
            bool $forUpdate = false): array|null {
        /* @phpstan-ignore return.type */
        return parent::row_Where($conditions, $groupExtension, $forUpdate);
    }

    /** 
     * @return list<_T_TRPrzelewy24_Tests>
     */
    #[Override]
    public function select_ByPKs(array $pks, string $groupExtension = ''): array {
        return parent::select_ByPKs($pks, $groupExtension);
    }

    /** 
     * @return list<_T_TRPrzelewy24_Tests>
     */
    #[Override]
    public function select_Where(array $conditions = [], string $groupExtension = '',
            bool $tableOnly = false): array {
        return parent::select_Where($conditions, $groupExtension);
    }

    /** 
     * @return _T_TRPrzelewy24_Tests
     */
    #[Override]
    public function stripRow_TableColumnsOnly(array $row): array {
        /* @phpstan-ignore return.type */
        return parent::stripRow_TableColumnsOnly($row);
    }
}
