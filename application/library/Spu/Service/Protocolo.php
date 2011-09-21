<?php
/**
 * Classe para acessar os serviços de Protocolo do SPU
 * 
 * @author Bruno Cavalcante <brunofcavalcante@gmail.com>
 * @package SPU
 * @see Spu_Service_Abstract
 */
class Spu_Service_Protocolo extends Spu_Service_Abstract
{
    private $_protocoloBaseUrl = 'spu/protocolo';
    
    public function getProtocolo($nodeUuid)
    {
        $url = $this->getBaseUrl() . "/" . $this->_protocoloBaseUrl . "/get/$nodeUuid";
        $result = $this->_doAuthenticatedGetRequest($url);

        return $this->loadFromHash(array_pop($result['Protocolo']));
    }

    public function getProtocolos()
    {
        $url = $this->getBaseUrl() . "/" . $this->_protocoloBaseUrl .  "/listar";
        $result = $this->_doAuthenticatedGetRequest($url);

        return $this->_loadManyFromHash($result['Protocolos']);
    }

    public function alterar($id, $postData)
    {
        $url = $this->getBaseUrl() . "/" . $this->_protocoloBaseUrl . "/editar/$id";
        $result = $this->_doAuthenticatedPostRequest($url, $postData);
        
        return $this->loadFromHash($result['Protocolo'][0]);
    }
    
    public function getTodosProtocolosPaginado($offset = 0, $pageSize = 20, $filter = null)
    {
        $filter = urlencode($filter);
        $url = "{$this->getBaseUrl()}/{$this->_protocoloBaseUrl}/listarTodosPaginado/$offset/$pageSize/?s=$filter";
        
        $result = $this->_doAuthenticatedGetRequest($url);
        
        return $this->_loadManyFromHash($result['Protocolos'][0]);
    }
    
    public function getProtocolosDestino($protocoloOrigemId, $tipoProcessoId, $filter, $offset, $pageSize)
    {
        $url = "{$this->getBaseUrl()}/{$this->_protocoloBaseUrl}/listardestinos?protocoloRaizId={$protocoloOrigemId}";
        $url .= "&tipoProcessoId={$tipoProcessoId}&filter={$filter}&offset={$offset}&pageSize={$pageSize}";
        
        $result = $this->_doAuthenticatedGetRequest($url);
                
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