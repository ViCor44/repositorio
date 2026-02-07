<?php

namespace App\Models;

use Core\Model;
use PDO;

class Parque extends Model {

    public function all() {

        return $this->db
            ->query("SELECT * FROM parques ORDER BY nome")
            ->fetchAll(PDO::FETCH_ASSOC);
    }
}
