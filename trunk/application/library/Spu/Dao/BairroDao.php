<?php
require_once('BaseDao.php');
Loader::loadEntity('Bairro');
class BairroDao extends BaseDao
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
    
    protected function _loadFromHash($hash)
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
        	$bairros[] = $this->_loadFromHash($hashBairro[0]);
        }
        
        return $bairros;
    }
}