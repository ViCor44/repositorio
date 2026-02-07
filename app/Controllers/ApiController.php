<?php
namespace App\Controllers;

class ApiController {

    public function edificiosPorZona() {

        header('Content-Type: application/json');

        $zonaId = $_GET['zona_id'] ?? null;

        if (!$zonaId) {
            echo json_encode([]);
            return;
        }

        $model = new \App\Models\Edificio();

        echo json_encode(
            $model->byZona($zonaId)
        );
    }

    public function salasPorEdificio() {

        header('Content-Type: application/json');

        $edId = $_GET['edificio_id'] ?? null;

        if (!$edId) {
            echo json_encode([]);
            return;
        }

        $model = new \App\Models\Sala();

        echo json_encode(
            $model->byEdificio($edId)
        );
    }
}
