<?php

namespace App\Models;

class Edificio extends \Core\Model {

    public function byZona($zonaId) {

        $stmt = $this->db->prepare(
            "SELECT * FROM edificios WHERE zona_id=?"
        );
        $stmt->execute([$zonaId]);

        return $stmt->fetchAll();
    }

    public function create($zonaId,$nome) {

        $stmt = $this->db->prepare(
            "INSERT INTO edificios (zona_id,nome) VALUES (?,?)"
        );

        $stmt->execute([$zonaId,$nome]);
    }

    public function update(int $id,string $nome) {

        $stmt = $this->db->prepare(
            "UPDATE edificios SET nome=? WHERE id=?"
        );

        $stmt->execute([$nome,$id]);
    }

    public function delete(int $id) {

        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM salas WHERE edificio_id=?"
        );
        $stmt->execute([$id]);

        if ($stmt->fetchColumn() > 0) {
            return false;
        }

        $stmt = $this->db->prepare(
            "DELETE FROM edificios WHERE id=?"
        );

        return $stmt->execute([$id]);
    }

}
