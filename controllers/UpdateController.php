<?php

// esta parte cuida da logica das chamadas a listas

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Update.php';
require_once __DIR__ . '/../utils/SalvaArquivos.php';
require_once __DIR__ . '/../utils/Response.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key; // Adicione isso aqui

class UpdateController extends BaseController {

    public function repositorios($dataToken) {

        if ($this->requestMethod !== 'PUT') {
            Response::methodNotAllowed();
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        $userModel = new Update($this->db);
        $UpdateRepositorios = $userModel->UpdateRepositorios($data, $dataToken['id']);

        if ($UpdateRepositorios['success'] ===  true) {
            Response::ok([
                "message" => "Repositorio atualizado com sucesso!",
                "token" => $dataToken['token']
            ]);
        } else {
            Response::unprocessableEntity([
                "message" => "O correu um erro ao atualizar o repositorio"
            ]);
  
        }
    }
}
?>