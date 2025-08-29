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
	public function comunicacaoCloudfler($link) {
	    try {
	        if (empty($link)) {
	            throw new Exception('Link não fornecido.');
	        }
	        
	        // Sua chave API do Cloudflare
	        $apiKey = $_ENV['API_KEY_CLOUDFLER'];
			$email = $_ENV['EMAIL_CLOUDFLER'];
	        $zoneId = $_ENV['ZONE_ID_CLOUDFLER'];
	        // URL da API do Cloudflare para purgar cache
	        $url = "https://api.cloudflare.com/client/v4/zones/{$zoneId}/purge_cache";
	        
	        // Preparar os dados para a requisição
	        $data = [
	            'files' => [$link] // Pode ser array com múltiplos URLs
	        ];
	        
	        // Configurar a requisição cURL
	        $ch = curl_init();
	        curl_setopt($ch, CURLOPT_URL, $url);
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
	        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
	        curl_setopt($ch, CURLOPT_HTTPHEADER, [
	            'X-Auth-Email: ' . $email,
	            'Authorization: Bearer ' . $apiKey,
	            'Content-Type: application/json'
	        ]);
	        
	        // Executar a requisição
	        $response = curl_exec($ch);
	        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	        $error = curl_error($ch);
	        curl_close($ch);
	        
	        if ($error) {
	            throw new Exception('Erro na requisição: ' . $error);
	        }
	        
	        $result = json_decode($response, true);
	        
	        if (!$result['success']) {
		        return [
		            'success' => false,
		            'message' => 'erro ao se comunicar',
		            'response' => $result
		        ];

	        }
	        
	        return [
	            'success' => true,
	            'message' => 'Cache limpo com sucesso',
	            'response' => $result
	        ];
	        
	    } catch (Exception $e) {
	        return [
	            'success' => false,
	            'message' => $e->getMessage(),
	            'link' => $link ?? ''
	        ];
	    }
	}
}
?>
