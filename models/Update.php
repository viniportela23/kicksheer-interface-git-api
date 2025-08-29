<?php 
// ambiente criado para fazer a busca ao banco voltada ao cardapio

require_once __DIR__ . '/BaseModel.php';
require_once __DIR__ . '/Busca.php';

class Update extends BaseModel {

	public function UpdateRepositorios($data, $id_user) {
	    // Validação básica dos dados recebidos
	    if (empty($data['id'])) {
	        return [
	            'success' => false,
	            'message' => 'ID é obrigatório'
	        ];
	    }

	    try {
	    	$userModel = new Buscas($this->db);
	    	$ListaAnuncio = $userModel->ConsultaCompletaBaseIDUserIDTabela($data['id'], $id_user, 'repositorios');

	        if (empty($ListaAnuncio['data'])) {
	            return [
	                'success' => false,
	                'message' => 'Repositorio não encontrado ou não pertence ao usuário'
	            ];
	        }

	        if ($data['link_repositorio'] === 'null' && $data['diretorio'] === 'null' && $data['link_page'] === 'null') {
	        	$data['link_repositorio'] = null;
				$data['diretorio'] = null;
	        	$data['link_page'] = null;
	        }

	        // Preenche os dados não fornecidos com os valores atuais
	        $fieldsToUpdate = [
	            'link_repositorio' => $data['link_repositorio'] ?? $ListaAnuncio['data']['link_repositorio'],
	            'diretorio' => $data['diretorio'] ?? $ListaAnuncio['data']['diretorio'],
	            'link_page' => $data['link_page'] ?? $ListaAnuncio['data']['link_page']
	        ];

	        // Construção da query de atualização
	        $query = "UPDATE repositorios SET 
	                 link_repositorio = :link_repositorio, 
	                 diretorio = :diretorio, 
	                 link_page = :link_page, 
	                 data_modificacao = NOW(), 
	                 status = :status
	                 WHERE id = :id AND id_user = :id_user";

	        $params = [
	            ':id' => $data['id'],
	            ':link_repositorio' => $fieldsToUpdate['link_repositorio'],
	            ':diretorio' => $fieldsToUpdate['diretorio'],
	            ':link_page' => $fieldsToUpdate['link_page'],
	            ':status' => $data['status'],
	            ':id_user' => $id_user            
	        ];
	        
	        $stmt = $this->executeQuery($query, $params);
	        
	        // Verifica se alguma linha foi afetada
	        if ($stmt->rowCount() > 0) {
	            return [
	                'success' => true,
	                'message' => 'Registro atualizado com sucesso',
	                'affected_rows' => $stmt->rowCount()
	            ];
	        } else {
	            return [
	                'success' => false,
	                'message' => 'Nenhum registro foi atualizado'
	            ];
	        }
	    } catch (Exception $e) {
	        return [
	            'success' => false,
	            'message' => 'Erro ao atualizar registro: ' . $e->getMessage()
	        ];
	    }
	}
}
?>
