<?php
require_once('BaseDao.php');
Loader::loadEntity('Assunto');
Loader::loadDao('ArquivoDao');
class AssuntoDao extends BaseDao
{
    private $_assuntosBaseUrl = 'spu/assuntos';
    private $_assuntosTicketUrl = 'ticket';
    
    public function getAssuntos()
    {
        $url = $this->getBaseUrl() . "/" . $this->_assuntosBaseUrl . "/listar";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        $resultJson = $curlObj->doGetRequest($url);
        $result = json_decode($resultJson, true);
        
        return $this->_loadManyFromHash($result['assuntos']);
    }
    
    public function getAssuntosPorTipoProcesso($idTipoProcesso)
    {
        $url = $this->getBaseUrl() . "/" . $this->_assuntosBaseUrl . "/listarportipoprocesso/$idTipoProcesso";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        $resultJson = $curlObj->doGetRequest($url);
        $result = json_decode($resultJson, true);
        
        return $this->_loadManyFromHash($result['assuntos']);
    }
    
    protected function _getNomeAjustadoNomeParaUrl($nome)
    {
        $nome = str_replace(' ', '%20', $nome);
        return $nome;
    }
    
    public function getAssunto($nodeUuid)
    {
        $url = $this->getBaseUrl() . "/" . $this->_assuntosBaseUrl . "/get/$nodeUuid";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        $resultJson = $curlObj->doGetRequest($url);
        $result = json_decode($resultJson, true);
        
        return $this->loadFromHash(array_pop(array_pop($result['Assunto'][0])));
    }
    
    public function inserir($postData)
    {    	
    	$url = $this->getBaseUrl() . "/" . $this->_assuntosBaseUrl . "/inserir";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        
        $result = $curlObj->doPostRequest($url, $postData);
        
        if ($this->isAlfrescoError($result)) {
            throw new Exception($this->getAlfrescoErrorMessage($result));
        }
        
        return $this->loadFromHash(array_pop(array_pop($result['Assunto'][0])));
    }
    
    public function editar($id, $postData)
    {       
        $url = $this->getBaseUrl() . "/" . $this->_assuntosBaseUrl . "/editar/$id";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        
        $result = $curlObj->doPostRequest($url, $postData);
        
        if ($this->isAlfrescoError($result)) {
            throw new Exception($this->getAlfrescoErrorMessage($result));
        }
        
        return $this->loadFromHash(array_pop(array_pop($result['Assunto'][0])));
    }
    
    public function loadFromHash($hash)
    {
    	$assunto = new Assunto();
    	
        $assunto->setNodeRef($this->_getHashValue($hash, 'noderef'));
        $assunto->setNome($this->_getHashValue($hash, 'nome'));
        $assunto->setCorpo($this->_getHashValue($hash, 'corpo'));
        $assunto->setNotificarNaAbertura($this->_getHashValue($hash, 'notificarNaAbertura') ? true : false);
        $assunto->setTipoProcesso($this->_loadTipoProcessoFromHash($this->_getHashValue($hash, 'tipoProcesso')));
        $assunto->setFormulario($this->_loadFormulario($assunto->getId()));
        
        return $assunto;
    }
    
    protected function _loadTipoProcessoFromHash($hash){
        $tipoProcesso = new TipoProcesso();
        if ($hash AND is_array($hash)) {
            $hash = array_pop($hash);
            $tipoProcesso->setNodeRef($this->_getHashValue($hash, 'noderef'));
            $tipoProcesso->setNome($this->_getHashValue($hash, 'nome'));
        }
        return $tipoProcesso;
    }
    
    protected function _loadFormulario($assuntoId)
    {
    	$arquivoDao = new ArquivoDao($this->getTicket());
    	$formulario = new Formulario();
    	try {
    	   $formularioData = $arquivoDao->getContentFromUrl(array('id' => $assuntoId));
    	   $formulario->setData($formularioData);
    	} catch (Exception $e) {
    		
    	}
    	
    	return $formulario;
    }
    
    protected function _loadManyFromHash($hash)
    {
        $hashAssuntos = array();
        foreach ($hash as $hashAssunto) {
        	//FIXME: Esse service nao esta respondendo coforme o padrão
            //$hashAssunto = array_pop($hashAssunto);
            $hashAssuntos[] = $this->loadFromHash($hashAssunto);
        }

        return $hashAssuntos;
    }
}