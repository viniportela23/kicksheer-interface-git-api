<?php 
// ambiente criado para fazer a busca ao banco voltada ao cardapio

require_once __DIR__ . '/BaseModel.php';
require_once __DIR__ . '/Busca.php';

class Adicao extends BaseModel {

	public function InsertRepositorio($data, $id_user) {
	    // Validação básica dos dados recebidos
	    if (empty($data['link_repositorio']) || empty($data['diretorio']) || empty($data['link_page'])) {
	        return [
	            'success' => false,
	            'message' => 'alguns dados estão faltando'
	        ];
	    }

	    try {
	        $query = "INSERT INTO repositorios 
	                 (link_repositorio, diretorio, link_page, data_criacao, data_modificacao, status, id_user) 
	                 VALUES 
	                 (:link_repositorio, :diretorio, :link_page, NOW(), NOW(), :status, :id_user)";
	        
	        $stmt = $this->executeQuery($query, [
	            ':link_repositorio' => $data['link_repositorio'],
	            ':diretorio' => $data['diretorio'],
	            ':link_page' => $data['link_page'],
	            ':status' => $data['status'],
	            ':id_user' => $id_user
	        ]);
	        
	        return [
	            'success' => true,
	            'message' => 'Registro inserido com sucesso',
	            'id' => $this->db->lastInsertId() // Retorna o ID do registro inserido
	        ];
	    } catch (Exception $e) {
	        return [
	            'success' => false,
	            'message' => 'Erro ao inserir registro: ' . $e->getMessage()
	        ];
	    }
	}

}
?>
