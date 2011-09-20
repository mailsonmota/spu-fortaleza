<?php
class Spu_Service_Arquivo extends Spu_Service_Abstract
{
    private $_processoBaseUrl = 'spu/processo';

    public function getArquivos($nodeUuid)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/arquivos/get/$nodeUuid";
        $result = $this->_doAuthenticatedGetRequest($url);
        
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
        $result = $this->_doAuthenticatedPostFormDataRequest($url, $postData);
        
        return $result;
    }

    public function salvarFormulario($postData)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/formulario/salvar";
        $result = $this->_doAuthenticatedPostRequest($url, $postData);
        
        return $result;
    }
    
    public function getArquivoDownloadUrl($hash)
    {
        $url = "{$this->getBaseUrl()}/api/node/workspace/SpacesStore/{$hash['id']}/content/{$hash['nome']}";
        $url = $this->addAlfTicketUrl($url);
        
        return $url;
    }
    
    public function getArquivoFormularioDownloadUrl($arquivoHash)
    {
        $url = $this->getBaseUrl() . "/spu/formulario/get/assunto/" . $arquivoHash['id'];
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
        $result = $this->_doAuthenticatedGetStringRequest($url);
        
        if (strpos($result, 'Internal Error') > -1) {
            throw new Exception('Erro ao capturar o formulario');
        }
        
        return $result;
    }
    
    public function getRespostasFormulario($processoId)
    {
    	$url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/formulario/get/$processoId";
        $result = $this->_doAuthenticatedGetStringRequest($url);
        
        $respostasFormulario = new Spu_Entity_RespostasFormulario();
        if ($this->_isValidRespostasXML($result)) {
            $respostasFormulario->loadFromXML($result);
        }
        
        return $respostasFormulario;
    }
    
    protected function _isValidRespostasXML($xml)
    {
        return (is_string($xml) AND strpos($xml, '<title>Apache') === false) ? true : false;
    }
    
    public function loadFromHash($hash)
    {
        $arquivo = new Spu_Entity_Arquivo();
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
