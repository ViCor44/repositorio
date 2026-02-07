<?php
namespace Core;

use Core\Database;

abstract class Model {

    protected $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }
}
