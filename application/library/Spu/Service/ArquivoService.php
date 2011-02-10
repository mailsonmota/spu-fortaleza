<?php
require_once('BaseService.php');
Loader::loadEntity('Arquivo');
Loader::loadEntity('RespostasFormulario');
class ArquivoService extends BaseService
{
	private $_processoBaseUrl = 'spu/processo';

	public function getArquivos($nodeUuid)
	{
		$url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/arquivos/get/$nodeUuid";
		$url = $this->addAlfTicketUrl($url);

		$curlObj = new CurlClient();
		$result = $curlObj->doGetRequest($url);


		if ($this->isAlfrescoError($result)) {
			throw new Exception($this->getAlfrescoErrorMessage($result));
		}

		return $this->loadManyFromHash($result);
	}

	/**
	 * $postData
	 *   $postData['destNodeUuid'] - an alfresco node id
	 *   $postData['fileToUpload'] - file address on local filesystem. ex.: @/tmp/filename.txt
	 */
	public function uploadArquivo($postData)
	{
		$url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/uploadarquivo";
		$url = $this->addAlfTicketUrl($url);

		$curlObj = new CurlClient();

		$result = $curlObj->doPostRequest($url, $postData, 'formdata');

		if ($this->isAlfrescoError($result)) {
			throw new Exception($this->getAlfrescoErrorMessage($result));
		}

		return $result;
	}

	public function salvarFormulario($postData)
	{
		$url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/formulario/salvar";
        $url = $this->addAlfTicketUrl($url);

        $curlObj = new CurlClient();

        $result = $curlObj->doPostRequest($url, $postData);
        
        if ($this->isAlfrescoError($result)) {
        	echo '<pre>'; var_dump($result); echo '</pre>'; exit;
            throw new Exception($this->getAlfrescoErrorMessage($result));
        }

        return $result;
	}
	
    public function getArquivoDownloadUrl($arquivoHash)
    {
        $url = $this->getBaseUrl() . "/api/node/workspace/SpacesStore/"
             . $arquivoHash['id'] . "/content/" . $arquivoHash['nome'];
        $url = $this->addAlfTicketUrl($url);
        return $url;
    }
    
	public function getArquivoFormularioDownloadUrl($arquivoHash)
	{
		$url = $this->getBaseUrl() . "/spu/formulario/get/assunto/"
		. $arquivoHash['id'];
		$url = $this->addAlfTicketUrl($url);
		return $url;
	}

	/**
	 *   Estrutura do $getData
	 *   $getData['id']
	 *   $getData['nome']
	 */
	public function getContentFromUrl($getData)
	{
		$url = $this->getArquivoFormularioDownloadUrl($getData);
		$curlObj = new CurlClient();
		$result = $curlObj->doGetRequest($url);
		
		if (strpos($result, 'Internal Error') > -1) {
			throw new Exception('Erro ao capturar o formulario');
		}
		
		return $result;
	}
	
	public function getRespostasFormulario($processoId)
	{
		$url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/formulario/get/$processoId";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        try {
            $result = $curlObj->doGetRequest($url);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        
        $respostasFormulario = new RespostasFormulario();
        if ($this->_isValidRespostasXML($result)) {
            $respostasFormulario->loadFromXML($result);
        }
        
        return $respostasFormulario;
	}
	
	protected function _isValidRespostasXML($xml)
	{
		return (is_string($xml) AND strpos($xml, '<title>Apache') == -1) ? true : false;
	}
	
	public function loadFromHash($hash)
	{
		$arquivo = new Arquivo();
        $arquivo->setId($this->_getHashValue($hash, 'id'));
        $arquivo->setNome($this->_getHashValue($hash, 'nome'));
        
        return $arquivo;
	}
	
	public function loadManyFromHash($hash)
	{
        $arquivos = Array();
        foreach ($hash as $hashArquivo) {
            $arquivos[] = $this->loadFromHash($hashArquivo);
        }
        
        return $arquivos;
	}
}
