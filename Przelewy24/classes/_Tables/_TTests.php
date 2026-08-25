<?php namespace EC\Przelewy24\_Tables;
defined('_ESPADA') or die(NO_ACCESS);

use E, EC;
use EC\Database;
use EC\Database\MDatabase;
use EC\Database\TTable;

/**
 *
 * @phpstan-type _T_RPrzelewy24_Tests array{
 *     Id: int,
 *     Info: string,
 * }
 */
class _TTests extends TTable {
    /**
     *
     * @param array $row
     * @return _T_RPrzelewy24_Tests
     */
    static public function AssertRow(array $row): array {
        /* @phpstan-ignore return.type */
        return $row;
    }

    /**
     *
     * @param array $rows
     * @return array<_T_RPrzelewy24_Tests>
     */
    static public function AssertRows(array $rows): array {
        return $rows;
    }


    public function __construct(MDatabase $db, $tablePrefix = 'p24_t') {
        parent::__construct($db, 'Przelewy24_Tests', $tablePrefix);

        $this->setColumns([
            'Id' => new Database\FInt(true, false), 
            'Info' => new Database\FText(true, 'medium'), 
        ]);
        $this->setPKs([ 'Id' ]);

    }
}
