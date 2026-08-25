<?php namespace EC\Przelewy24\_Tables;
defined('_ESPADA') or die(NO_ACCESS);

use E, EC;
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
    /**
     *
     * @param array $row
     * @return _T_RPrzelewy24_TransactionSecrets
     */
    static public function AssertRow(array $row): array {
        /* @phpstan-ignore return.type */
        return $row;
    }

    /**
     *
     * @param array $rows
     * @return array<_T_RPrzelewy24_TransactionSecrets>
     */
    static public function AssertRows(array $rows): array {
        return $rows;
    }


    public function __construct(MDatabase $db, $tablePrefix = 'p24_ts') {
        parent::__construct($db, 'Przelewy24_TransactionSecrets', $tablePrefix);

        $this->setColumns([
            'Id' => new Database\FInt(true, false), 
            'Secret' => new Database\FString(true, 128), 
            'CRC' => new Database\FString(true, 128), 
        ]);
        $this->setPKs([ 'Id' ]);

    }
}
