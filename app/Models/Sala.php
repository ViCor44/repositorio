<?php

namespace App\Models;

class Sala extends \Core\Model {

    public function byEdificio($edId) {

        $stmt = $this->db->prepare(
            "SELECT * FROM salas WHERE edificio_id=?"
        );
        $stmt->execute([$edId]);

        return $stmt->fetchAll();
    }

    public function create($edId,$nome) {

        $stmt = $this->db->prepare(
            "INSERT INTO salas (edificio_id,nome) VALUES (?,?)"
        );

        $stmt->execute([$edId,$nome]);
    }

    public function update(int $id,string $nome) {

        $stmt = $this->db->prepare(
            "UPDATE salas SET nome=? WHERE id=?"
        );

        $stmt->execute([$nome,$id]);
    }

    public function delete(int $id) {

        $stmt = $this->db->prepare(
            "DELETE FROM salas WHERE id=?"
        );

        return $stmt->execute([$id]);
    }

}
