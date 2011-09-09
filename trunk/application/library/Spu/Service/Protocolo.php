<?php
class Spu_Service_Protocolo extends Spu_Service_Abstract
{
    private $_protocoloBaseUrl = 'spu/protocolo';
    private $_protocoloTicketUrl = 'ticket';

    public function getProtocolo($nodeUuid)
    {
        $url = $this->getBaseUrl() . "/" . $this->_protocoloBaseUrl . "/get/$nodeUuid";
        $url = $this->addAlfTicketUrl($url);

        $curlObj = new CurlClient();
        $result = $curlObj->doGetRequest($url);

        if ($this->isAlfrescoError($result)) {
            throw new Exception($this->getAlfrescoErrorMessage($result));
        } 
        
        return $this->loadFromHash(array_pop(array_pop($result['Protocolo'][0])));
    }

    public function getProtocolos()
    {
        $url = $this->getBaseUrl() . "/" . $this->_protocoloBaseUrl .  "/listar";
        $url = $this->addAlfTicketUrl($url);

        $curlObj = new CurlClient();
        $result = $curlObj->doGetRequest($url);

        return $this->_loadManyFromHash($result['Protocolos'][0]);
    }

    public function getTodosProtocolos()
    {
        $url = $this->getBaseUrl() . "/" . $this->_protocoloBaseUrl . "/listarTodos";
        $url = $this->addAlfTicketUrl($url);

        $curlObj = new CurlClient();
        $result = $curlObj->doGetRequest($url);

        return $this->_loadManyFromHash($result['Protocolos'][0]);
    }

    public function alterar($id, $postData)
    {
        $url = $this->getBaseUrl() . "/" . $this->_protocoloBaseUrl . "/editar/$id";
        $url = $this->addAlfTicketUrl($url);

        $curlObj = new CurlClient();

        $result = $curlObj->doPostRequest($url, $postData);

        if ($this->isAlfrescoError($result)) {
            throw new Exception($this->getAlfrescoErrorMessage($result));
        }

        return $this->loadFromHash($result['Protocolo'][0]);
    }
    
    public function getTodosProtocolosPaginado($offset = 0, $pageSize = 20, $filter = null)
    {
        $filter = urlencode($filter);
        $url = $this->getBaseUrl() . "/" 
                                    . $this->_protocoloBaseUrl 
                                    . "/listarTodosPaginado" 
                                    . "/$offset" 
                                    . "/$pageSize"  
                                    . "/?s=$filter";
        $url = $this->addAlfTicketUrl($url);

        $curlObj = new CurlClient();
        $result = $curlObj->doGetRequest($url);

        if (!isset($result['Protocolos'])) {
        	return false;
        }
        
        return $this->_loadManyFromHash($result['Protocolos'][0]);
    }
    
    public function getProtocolosDestino($protocoloOrigemId, $tipoProcessoId, $filter, $offset, $pageSize)
    {
//        $url = $this->getBaseUrl() . "/" . $this->_protocoloBaseUrl . "/listardestinos/";
//        $url .= "{$protocoloOrigemId}/{$tipoProcessoId}/{$filter}/{$offset}/{$pageSize}";
        
        $url = $this->getBaseUrl() . "/" . $this->_protocoloBaseUrl . "/listardestinos?";
        $url .= "protocoloRaizId={$protocoloOrigemId}&tipoProcessoId={$tipoProcessoId}&filter={$filter}&offset={$offset}&pageSize={$pageSize}";
        
        $url = $this->addAlfTicketUrl($url);

        $curlObj = new CurlClient();
        $result = $curlObj->doGetRequest($url);
        
        return $this->_loadManyFromHash($result['Protocolos'][0]);
    }

    public function loadFromHash($hash)
    {
        $protocolo = new Spu_Entity_Protocolo();
        
        $protocolo->setNodeRef($this->_getHashValue($hash, 'noderef'));
        $protocolo->setNome($this->_getHashValue($hash, 'nome'));
        $protocolo->setParent($this->_loadParentFromHash($this->_getHashValue($hash, 'parentId')));
        $protocolo->setDescricao($this->_getHashValue($hash, 'descricao'));
        $protocolo->setNivel($this->_getHashValue($hash, 'nivel'));
        $protocolo->setPath($this->_getHashValue($hash, 'path'));
        
        return $protocolo;
    }
    
    protected function _loadParentFromHash($id)
    {
        $parent = new Spu_Entity_Protocolo();
        $parent->setNodeRef($id);
        return $parent;
    }
    
    protected function _loadManyFromHash($hash)
    {
        $protocolos = array();
        if ($hash) {
	        foreach ($hash as $hashProtocolo) {
	            $hashProtocolo = array_pop($hashProtocolo);
	            $protocolos[] = $this->loadFromHash($hashProtocolo);
	        }
        }

        return $protocolos;
    }
}