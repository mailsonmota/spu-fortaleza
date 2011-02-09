<?php
require_once('BaseDao.php');
class ArquivoDao extends BaseDao
{
	private $_processoBaseUrl = 'spu/processo';

	public function getArquivos($nodeUuid)
	{
		$url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/arquivos/get/$nodeUuid";
		$url = $this->addAlfTicketUrl($url);

		$curlObj = new CurlClient();
		$resultJson = $curlObj->doGetRequest($url);
		$result = json_decode($resultJson, true);

		if ($this->isAlfrescoError($result)) {
			throw new Exception($this->getAlfrescoErrorMessage($result));
		}

		return $result;
	}

	/**
	 * Estrutura do $postData
	 *   $postData['processoId']
	 *   $postData['fileContent']
	 */
	public function uploadArquivo($postData)
	{
		// TODO Revisar web script "uploadarquivo"
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
		
		/*if ($this->isAlfrescoError($result)) {
		 throw new Exception($this->getAlfrescoErrorMessage($result));
		}*/

		return $result;
	}
	
	public function getRespostasFormulario($processoId)
	{
		$url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/formulario/get/$processoId";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        $result = $curlObj->doGetRequest($url);
        
        return $result;
	}
}