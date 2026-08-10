<?php namespace EC\Przelewy24\_Tables;
defined('_ESPADA') or die(NO_ACCESS);

use E, EC;
use EC\ABData\ABDataTable;
use EC\Database;
use EC\Database\MDatabase;
use EC\Database\TTable;

/**
 *
 * @phpstan-type _T_RPrzelewy24_TransactionSecrets array{
 *     Id: int,
 *     Secret: string,
 *     CRC: string,
 * }
 */
class _TTransactionSecrets extends TTable {
    use ABDataTable;

    public function __construct(MDatabase $db, $tablePrefix = 't') {
        parent::__construct($db, 'Przelewy24_TransactionSecrets', $tablePrefix);

        $this->setColumns([
            'Id' => new Database\FInt(true, false), 
            'Secret' => new Database\FString(true, 128), 
            'CRC' => new Database\FString(true, 128), 
        ]);
        $this->setPKs([ 'Id' ]);
    }

    /**
     *
     * @param array $row
     * @return _T_RPrzelewy24_TransactionSecrets
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
     * @return array<_T_RPrzelewy24_TransactionSecrets>
     */
    public function assertRows(array $rows): array {
        return $rows;
    }
}
