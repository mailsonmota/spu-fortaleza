<?php
require_once('BaseService.php');
Loader::loadEntity('Protocolo');
class ProtocoloService extends BaseService
{
    private $_protocoloBaseUrl = 'spu/protocolo';
    private $_protocoloTicketUrl = 'ticket';

    public function getProtocolo($nodeUuid)
    {
        $url = $this->getBaseUrl() . "/" . $this->_protocoloBaseUrl . "/get/$nodeUuid";
        $url = $this->addAlfTicketUrl($url);

        $curlObj = new CurlClient();
        $resultJson = $curlObj->doGetRequest($url);
        $result = json_decode($resultJson, true);

        return $this->loadFromHash(array_pop(array_pop($result['Protocolo'][0])));
    }

    public function getProtocolos()
    {
        $url = $this->getBaseUrl() . "/" . $this->_protocoloBaseUrl .  "/listar";
        $url = $this->addAlfTicketUrl($url);

        $curlObj = new CurlClient();
        $resultJson = $curlObj->doGetRequest($url);
        $result = json_decode($resultJson, true);

        return $this->_loadManyFromHash($result['Protocolos'][0]);
    }

    public function getTodosProtocolos()
    {
        $url = $this->getBaseUrl() . "/" . $this->_protocoloBaseUrl . "/listarTodos";
        $url = $this->addAlfTicketUrl($url);

        $curlObj = new CurlClient();
        $resultJson = $curlObj->doGetRequest($url);
        $result = json_decode($resultJson, true);

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
    	$url = $this->getBaseUrl() . "/" . $this->_protocoloBaseUrl . "/listarTodosPaginado/$offset/$pageSize/?s=$filter";
        $url = $this->addAlfTicketUrl($url);

        $curlObj = new CurlClient();
        $resultJson = $curlObj->doGetRequest($url);
        $result = json_decode($resultJson, true);

        return $this->_loadManyFromHash($result['Protocolos'][0]);
    }

    public function loadFromHash($hash)
    {
    	$protocolo = new Protocolo();
    	
        $protocolo->setNodeRef($this->_getHashValue($hash, 'noderef'));
        $protocolo->setNome($this->_getHashValue($hash, 'nome'));
        $protocolo->setParent($this->_loadParentFromHash($this->_getHashValue($hash, 'parentId')));
        $protocolo->setDescricao($this->_getHashValue($hash, 'descricao'));
        $protocolo->setOrgao($this->_getHashValue($hash, 'orgao'));
        $protocolo->setLotacao($this->_getHashValue($hash, 'lotacao'));
        $protocolo->setRecebePelosSubsetores(($this->_getHashValue($hash, 'recebePelosSubsetores') == '1') ? true : false);
        $protocolo->setRecebeMalotes(($this->_getHashValue($hash, 'recebeMalotes') == '1') ? true : false);
        $protocolo->setNivel($this->_getHashValue($hash, 'nivel'));
        $protocolo->setPath($this->_getHashValue($hash, 'path'));
        
        return $protocolo;
    }
    
    protected function _loadParentFromHash($id)
    {
        $parent = new Protocolo();
        $parent->setNodeRef($id);
        return $parent;
    }
    
    protected function _loadManyFromHash($hash)
    {
        $protocolos = array();
        foreach ($hash as $hashProtocolo) {
            $hashProtocolo = array_pop($hashProtocolo);
            $protocolos[] = $this->loadFromHash($hashProtocolo);
        }

        return $protocolos;
    }
}