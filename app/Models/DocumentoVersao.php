<?php

namespace App\Models;

use Core\Model;

class DocumentoVersao extends Model {

    public function create(array $data) {

        $stmt = $this->db->prepare("
            INSERT INTO documento_versoes
            (documento_id,versao,ficheiro_path,formato,tamanho,criado_por,is_ativo)
            VALUES
            (:doc,:versao,:path,:formato,:tam,:user,:ativo)
        ");

        $stmt->execute([
            'doc' => $data['documento_id'],
            'versao' => $data['versao'],
            'path' => $data['ficheiro_path'],
            'formato' => $data['formato'],
            'tam' => $data['tamanho'],
            'user' => $data['criado_por'],
            'ativo' => $data['is_ativo'],
        ]);
    }
}
