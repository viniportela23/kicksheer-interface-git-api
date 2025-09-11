<?php

// esta parte cuida da logica acoes q seram feitas externamentes

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Busca.php';
require_once __DIR__ . '/../models/Acao.php';
require_once __DIR__ . '/../utils/Response.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key; // Adicione isso aqui

class AcaoController extends BaseController {

    public function repositorios($dataToken) {

        if ($this->requestMethod !== 'PUT') {
            Response::methodNotAllowed();
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        $userModel = new Buscas($this->db);
        $ListaRepositorios = $userModel->ConsultaCompletaBaseIDUserIDTabela($data["id"], $dataToken['id'], 'repositorios');

        $operacao = null;

        if ($data["modo"] !== null) {
            if ($data["modo"] === "1") {
                $operacao = 'clone';
            } elseif ($data["modo"] === "2") {
                $operacao = 'pull origin main';
            } elseif ($data["modo"] === "3") {
                $operacao = 'restore';
            } else {
                Response::unprocessableEntity([
                "message" => "Opção invalida."
            ]);

            }
        }

        if (!empty($ListaRepositorios["data"]["diretorio"]) && $operacao !== null) {
            if ($operacao === "clone") {
                $prompt = ' git ' . $operacao . ' ' . $ListaRepositorios["data"]["link_repositorio"] . '.git ' . $ListaRepositorios["data"]["diretorio"];
            } else {
                $prompt = 'cd ' . $ListaRepositorios["data"]["diretorio"] . ' && git ' . $operacao;
            }

            $modelAcaoRepositorio = new Acao($this->db);
            $AcaoRepositorio = $modelAcaoRepositorio->execucaoCodigoPrompt($prompt);

            if ($AcaoRepositorio['success'] ===  true) {
                Response::ok([
                    "message" => "Acao realizada com sucesso!",
                    "token" => $dataToken['token']
                ]);
            } else {
                Response::unprocessableEntity([
                    "message" => $AcaoRepositorio,
                    "comando" => $prompt,       
                ]);
      
            }

        } else {
            Response::unprocessableEntity([
                "message" => $ListaRepositorios
            ]);

        }

    }
    public function cloudfler($dataToken) {

        if ($this->requestMethod !== 'PUT') {
            Response::methodNotAllowed();
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        $userModel = new Buscas($this->db);
        $ListaRepositorios = $userModel->ConsultaCompletaBaseIDUserIDTabela($data["id"], $dataToken['id'], 'repositorios');

        if (!empty($ListaRepositorios["data"]['link_page'])) {

            $modelAcaoRepositorio = new Acao($this->db);
            $AcaoRepositorio = $modelAcaoRepositorio->comunicacaoCloudfler($ListaRepositorios["data"]['link_page']);

            if ($AcaoRepositorio['success'] ===  true) {
                Response::ok([
                    "message" => "Acao realizada com sucesso!",
                    "token" => $dataToken['token']
                ]);
            } else {
                Response::unprocessableEntity([
                    "message" => $AcaoRepositorio
                ]);
      
            }
        } else {
            Response::unprocessableEntity([
                "message" => "Erro ao se comunicar com o banco."
            ]);

        }

    }
    public function terminal($dataToken) {

        if ($this->requestMethod !== 'PUT') {
            Response::methodNotAllowed();
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);


        if (!empty($data["shell"])) {

            $modelAcaoRepositorio = new Acao($this->db);
            $AcaoRepositorio = $modelAcaoRepositorio->execucaoCodigoPrompt($data["shell"]);

            if ($AcaoRepositorio['success'] ===  true) {
                Response::ok([
                    "message" => $AcaoRepositorio,
                    "token" => $dataToken['token']
                ]);
            } else {
                Response::unprocessableEntity([
                    "message" => $AcaoRepositorio,
                    "comando" => $data["shell"],       
                ]);
      
            }
        } else {
            Response::unprocessableEntity([
                "message" => "Erro ao se comunicar com o banco."
            ]);

        }

    }
}
?>