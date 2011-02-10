<?php
require_once('BaseService.php');
Loader::loadClassification('Bairro');
class BairroService extends BaseService
{
    private $_bairrosBaseUrl = 'spu/bairros';
    private $_bairrosTicketUrl = 'ticket';
    
    public function getBairros()
    {
        $url = $this->getBaseUrl() . "/" . $this->_bairrosBaseUrl . "/listar";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        $resultJson = $curlObj->doGetRequest($url);
        $result = json_decode($resultJson, true);
        
        return $this->_loadManyFromHash($result['Bairros']);
    }
    
    public function loadFromHash($hash)
    {
    	$bairro = new Bairro();
    	
        $bairro->setNodeRef($this->_getHashValue($hash, 'noderef'));
        $bairro->setNome($this->_getHashValue($hash, 'nome'));
        $bairro->setDescricao($this->_getHashValue($hash, 'descricao'));
        
        return $bairro;
    }
    
    protected function _loadManyFromHash($hash)
    {
        $bairros = array();
        foreach ($hash[0] as $hashBairro) {
        	$bairros[] = $this->loadFromHash($hashBairro[0]);
        }
        
        return $bairros;
    }
}