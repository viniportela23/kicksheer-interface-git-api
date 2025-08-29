<?php

// esta parte cuida da logica dos delete

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Deleta.php';
require_once __DIR__ . '/../utils/Response.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key; // Adicione isso aqui

class DeletaController extends BaseController {

    public function repositorios($dataToken) {

        if ($this->requestMethod !== 'DELETE') {
            Response::methodNotAllowed();
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        $userModel = new Deletar($this->db);
        $DeletarBaseIDUserIDTabel = $userModel->DeletarBaseIDUserIDTabel($data['id'], $dataToken['id'], 'repositorios');

        if ($DeletarBaseIDUserIDTabel['success'] ===  true) {
            Response::ok([
                "message" => "Anúnciante deletado com sucesso!",
                "token" => $dataToken['token']
            ]);
        } else {
            Response::unprocessableEntity([
                "message" => "O correu um erro ao deletar anúncio"
            ]);
  
        }
    }
}
?>