<?php
/**
 * Classe para acessar os serviços de Prioridade dos Processos do SPU
 * 
 * @author Bruno Cavalcante <brunofcavalcante@gmail.com>
 * @package SPU
 * @see Spu_Service_Abstract
 */
class Spu_Service_Prioridade extends Spu_Service_Abstract
{
    /**
	 * URL Base dos serviços (a ser acrescentada à url dos serviços do Alfresco)
	 * @var string
	 */
    private $_baseUrl = 'spu/processo';
    
    /**
     * Retorna todas as opções de Prioridade do SPU
     * 
     * @return Spu_Entity_Classification_Prioridade[]
     */
    public function fetchAll()
    {
        $url = $this->getBaseUrl() . "/" . $this->_baseUrl . "/prioridades/listar";
        $result = $this->_doAuthenticatedGetRequest($url);
        
        return $this->_loadManyFromHash($result['Prioridades'][0]);
    }
    
    /**
     * Carrega uma Prioridade à partir de um hash
     * 
     * @param array $hash
     * @return Spu_Entity_Classification_Prioridade
     */
    public function loadFromHash($hash)
    {
        $prioridade = new Spu_Entity_Classification_Prioridade();
        
        $prioridade->setNodeRef($this->_getHashValue($hash, 'noderef'));
        $prioridade->setNome($this->_getHashValue($hash, 'nome'));
        $prioridade->setDescricao($this->_getHashValue($hash, 'descricao'));
        
        return $prioridade;
    }
    
    /**
     * Carrega várias Prioridades à partir de um hash
     * 
     * @param array $hash
     * @return Spu_Entity_Classification_Prioridade[]
     */
    protected function _loadManyFromHash($hash)
    {
        $prioridades = array();
        foreach ($hash as $hashPrioridade) {
            $prioridades[] = $this->loadFromHash($hashPrioridade[0]);
        }
        
        return $prioridades;
    }
}