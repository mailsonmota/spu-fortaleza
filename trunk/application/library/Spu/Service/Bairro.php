<?php
/**
 * Classe para acessar os serviços de Bairro do SPU
 * 
 * @author Bruno Cavalcante <brunofcavalcante@gmail.com>
 * @package SPU
 * @see Spu_Service_Abstract
 */
class Spu_Service_Bairro extends Spu_Service_Abstract
{
	/**
	 * URL Base dos serviços (a ser acrescentada à url dos serviços do Alfresco)
	 * @var string
	 */
    private $_bairrosBaseUrl = 'spu/bairros';
    
    /**
     * Retorna os Bairros cadastrados no SPU
     * 
     * @return Spu_Entity_Classification_Bairro[]
     */
    public function getBairros()
    {
        $url = $this->getBaseUrl() . "/" . $this->_bairrosBaseUrl . "/listar";
        $result = $this->_doAuthenticatedGetRequest($url);
        
        return $this->_loadManyFromHash($result['Bairros']);
    }
    
    /**
     * Carrega o Bairro através de um hash
     * 
     * @param array $hash
     * @return Spu_Entity_Classification_Bairro
     */
    public function loadFromHash($hash)
    {
        $bairro = new Spu_Entity_Classification_Bairro();
        
        $bairro->setNodeRef($this->_getHashValue($hash, 'noderef'));
        $bairro->setNome($this->_getHashValue($hash, 'nome'));
        $bairro->setDescricao($this->_getHashValue($hash, 'descricao'));
        
        return $bairro;
    }
    
    /**
     * Carrega vários bairros através de um hash
     * 
     * @param array $hash
     * @return Spu_Entity_Classification_Bairro[]
     */
    protected function _loadManyFromHash($hash)
    {
        $bairros = array();
        foreach ($hash[0] as $hashBairro) {
            $bairros[] = $this->loadFromHash($hashBairro[0]);
        }
        
        return $bairros;
    }
}