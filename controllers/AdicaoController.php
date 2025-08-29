<?php

// esta parte cuida da logica das chamadas a listas

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Adicao.php';
require_once __DIR__ . '/../utils/Response.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key; // Adicione isso aqui

class AdicaoController extends BaseController {

    public function repositorios($dataToken) {

        if ($this->requestMethod !== 'POST') {
            Response::methodNotAllowed();
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        $userModel = new Adicao($this->db);
        $InsertRepositorio = $userModel->InsertRepositorio($data, $dataToken['id']);

        if ($InsertRepositorio['success'] ===  true) {
            Response::ok([
                "message" => "Repositorio inserido com sucesso!",
                "token" => $dataToken['token']
            ]);
        } else {
            Response::unprocessableEntity([
                "message" => "Erro ao inserir repositorio."
            ]);
  
        }
    }
}
?>