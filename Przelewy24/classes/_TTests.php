<?php namespace EC\Przelewy24;
defined('_ESPADA') or die(NO_ACCESS);

use E, EC,
    EC\Database;

class _TTests extends Database\TTable {
    public function __construct(EC\MDatabase $db, $tablePrefix = 't') {
        parent::__construct($db, 'Przelewy24_Tests', $tablePrefix);

        $this->setColumns([
            'Id' => new Database\FInt(true),
            'Info' => new Database\FText(true, 'medium'),
        ]);
    }
}
