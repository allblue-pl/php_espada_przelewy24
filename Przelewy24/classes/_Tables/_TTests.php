<?php namespace EC\Przelewy24\_Tables;
defined('_ESPADA') or die(NO_ACCESS);

use E, EC;
use EC\ABData\ABDataTable;
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
    use ABDataTable;

    public function __construct(MDatabase $db, $tablePrefix = 't') {
        parent::__construct($db, 'Przelewy24_Tests', $tablePrefix);

        $this->setColumns([
            'Id' => new Database\FInt(true, false), 
            'Info' => new Database\FText(true, 'medium'), 
        ]);
        $this->setPKs([ 'Id' ]);
    }

    /**
     *
     * @param array $row
     * @return _T_RPrzelewy24_Tests
     */
    public function assertRow(array $row, bool $stripRow = false): array {
        if ($stripRow)
            $row = $this->stripRow($row);

        /* @phpstan-ignore return.type */
        return $row;
    }

    /**
     *
     * @param array $rows
     * @return array<_T_RPrzelewy24_Tests>
     */
    public function assertRows(array $rows): array {
        return $rows;
    }
}
