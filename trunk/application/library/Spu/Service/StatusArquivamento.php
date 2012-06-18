<?php
/**
 * Classe para acessar os serviços de Status de Arquivamento dos Processos do SPU
 *
 * @author Bruno Cavalcante <brunofcavalcante@gmail.com>
 * @package SPU
 * @see Spu_Service_Abstract
 */
class Spu_Service_StatusArquivamento extends Spu_Service_Abstract
{
    /**
	 * URL Base dos serviços (a ser acrescentada à url dos serviços do Alfresco)
	 * @var string
	 */
    private $_baseUrl = 'spu/processo';
    
    /**
     * Retorna todas as opções de status de arquivamento
     * 
     * @return Spu_Entity_Classification_StatusArquivamento[]
     */
    public function fetchAll()
    {
        $url = $this->getBaseUrl() . "/" . $this->_baseUrl . "/statusarquivamento/listar";
        
        $name = $this->getNameForMethod('fetchAll');
        if (($result = $this->getCache()->load($name)) === false) {

            $result = $this->_doAuthenticatedGetRequest($url);

            $this->getCache()->save($result, $name);
        }
        
        
        return $this->_loadManyFromHash($result['Status'][0]);
    }
    
    /**
     * Carrega o status de arquivamento através de um hash
     * 
     * @param array $hash
     * @return Spu_Entity_Classification_StatusArquivamento
     */
    public function loadFromHash($hash)
    {
        $statusArquivamento = new Spu_Entity_Classification_StatusArquivamento();
        
        $statusArquivamento->setNodeRef($this->_getHashValue($hash, 'noderef'));
        $statusArquivamento->setNome($this->_getHashValue($hash, 'nome'));
        $statusArquivamento->setDescricao($this->_getHashValue($hash, 'descricao'));
        
        return $statusArquivamento;
    }
    
    /**
     * Carrega vários status de arquivamento através de um hash
     * 
     * @param array $hash
     * @return Spu_Entity_Classification_StatusArquivamento[]
     */
    protected function _loadManyFromHash($hash)
    {
        $statusArquivamento = array();
        foreach ($hash as $hashStatusArquivamento) {
            $statusArquivamento[] = $this->loadFromHash($hashStatusArquivamento[0]);
        }
        
        return $statusArquivamento;
    }
}