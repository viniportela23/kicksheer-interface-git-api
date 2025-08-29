<?php

// esta parte cuida da logica das chamadas a listas

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Busca.php';
require_once __DIR__ . '/../utils/Response.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key; // Adicione isso aqui

class ListaController extends BaseController {

    public function repositorios($dataToken) {

        if ($this->requestMethod !== 'GET') {
            Response::methodNotAllowed();
            return;
        }

        $status = $_GET['status'] ?? null;
        $id = $_GET['id'] ?? null;

        $userModel = new Buscas($this->db);

        if (!empty($id)) {
            $ListaAnuncios = $userModel->ConsultaCompletaBaseIDUserIDTabela($id, $dataToken['id'], 'repositorios');

        } elseif (!empty($status)) {
            $ListaAnuncios = $userModel->ConsultaCompletaBaseIDUserStatus($status, $dataToken['id'], 'repositorios');

        } else {
            $ListaAnuncios = $userModel->ConsultaCompletaBaseIDUser($dataToken['id'], 'repositorios');
        }


        if (!empty($ListaAnuncios['data']) ) {
            Response::ok([
                "dados" => $ListaAnuncios['data'],
                "token" => $dataToken['token']
            ]);
        }
        elseif ($ListaAnuncios['message'] === 'Nenhum dado encontrado') {
            Response::ok([
                "message" => "Nenhum dado encontrado",
                "token" => $dataToken['token']
            ]);
        } else {
            Response::unprocessableEntity([
                "message" => "O correu um erro ao consultar lista"
            ]);
  
        }
    }

}

?>