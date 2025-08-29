<?php 
// ambiente criado para fazer a busca ao banco voltada ao cardapio

require_once __DIR__ . '/BaseModel.php';
require_once __DIR__ . '/Busca.php';

class Acao extends BaseModel {

	public function execucaoCodigoPrompt($prompt) {
	    try {
	        if (empty($prompt)) {
	            throw new Exception('Prompt não fornecido');
	        }
	         
	        $output = [];
	        $return_var = 0;
	        
	        exec($prompt . " 2>&1", $output, $return_var);
	        
	        if ($return_var !== 0) {
	            throw new Exception('Comando falhou com código: ' . $return_var);
	        }

	        return [
	            'success' => true,
	            'message' => 'Comando executado com sucesso',
	            'output' => implode("\n", $output),
	            'exit_code' => $return_var
	        ];
	        
	    } catch (Exception $e) {
	        return [
	            'success' => false,
	            'message' => $e->getMessage(),
	            'output' => isset($output) ? implode("\n", $output) : ''
	        ];
	    }
	}
}
?>
