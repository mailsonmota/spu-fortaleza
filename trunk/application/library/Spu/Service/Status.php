<?php
/**
 * Classe para acessar os serviços de Status dos Processos do SPU
 * 
 * @author Bruno Cavalcante <brunofcavalcante@gmail.com>
 * @package SPU
 * @see Spu_Service_Abstract
 */
class Spu_Service_Status extends Spu_Service_Abstract
{
	/**
	 * URL Base dos serviços (a ser acrescentada à url dos serviços do Alfresco)
	 * @var string
	 */
    private $_baseUrl = 'spu/processo';
    
    /**
     * Retorna todas as opções de status de processo
     * 
     * @return Spu_Entity_Classification_Status[]
     */
    public function listar()
    {
    	$url = $this->getBaseUrl() . "/" . $this->_baseUrl . "/status/listar";
    	$result = $this->_doAuthenticatedGetRequest($url);
        
        return $this->_loadManyFromHash($result['Status'][0]);
    }
    
    /**
     * Carrega vários status através de um hash
     * 
     * @param array $hash
     * @return Spu_Entity_Classification_Status[]
     */
    protected function _loadManyFromHash($hash)
    {
        $status = array();
        foreach ($hash as $hashStatus) {
            $status[] = $this->loadFromHash($hashStatus[0]);
        }
        
        return $status;
    }
    
    /**
     * Carrega o status através de um hash
     * 
     * @param array $hash
     * @return Spu_Entity_Classification_Status
     */
    public function loadFromHash($hash)
    {
        $status = new Spu_Entity_Classification_Status();
        
        $status->setNodeRef($this->_getHashValue($hash, 'noderef'));
        $status->setNome($this->_getHashValue($hash, 'nome'));
        $status->setDescricao($this->_getHashValue($hash, 'descricao'));
        
        return $status;
    }
}