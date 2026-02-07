<?php

namespace App\Models;

class Zona extends \Core\Model {

    public function all() {
        return $this->db->query(
            "SELECT * FROM zonas ORDER BY nome"
        )->fetchAll();
    }

    public function create($nome) {
        $stmt = $this->db->prepare(
            "INSERT INTO zonas (nome) VALUES (?)"
        );
        $stmt->execute([$nome]);
    }

    public function update(int $id, string $nome) {

        $stmt = $this->db->prepare(
            "UPDATE zonas SET nome=? WHERE id=?"
        );

        $stmt->execute([$nome,$id]);
    }

    public function delete(int $id) {

        // só apaga se não tiver edifícios
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM edificios WHERE zona_id=?"
        );
        $stmt->execute([$id]);

        if ($stmt->fetchColumn() > 0) {
            return false;
        }

        $stmt = $this->db->prepare(
            "DELETE FROM zonas WHERE id=?"
        );

        return $stmt->execute([$id]);
    }

}
