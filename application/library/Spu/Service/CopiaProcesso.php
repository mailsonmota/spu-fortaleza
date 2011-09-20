<?php
/**
 * Classe para acessar os serviços de Cópia de Processo do SPU
 * 
 * @author Bruno Cavalcante <brunofcavalcante@gmail.com>
 * @package SPU
 * @see Spu_Service_Abstract
 */
class Spu_Service_CopiaProcesso extends Spu_Service_Abstract
{
	/**
	 * URL Base dos serviços (a ser acrescentada à url dos serviços do Alfresco)
	 * @var string
	 */
    private $_processoBaseUrl = 'spu/processo';
    
    /**
     * Retorna as Cópias nos protocolos do usuário logado
     * 
     * @param integer $offset
     * @param integer $pageSize
     * @param string $filter
     * @return Spu_Entity_CopiaProcesso[]
     */
    public function getCopias($offset, $pageSize, $filter)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/copias/$offset/$pageSize/$filter";
        $result = $this->_doAuthenticatedGetRequest($url);
        
        return $this->_loadManyFromHash($result['Copias'][0]);
    }

    /**
     * Exclui as cópias informadas
     * 
     * @param array $postData array com os ids das cópias a excluir
     * @return array
     */
    public function excluirTodos($postData)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/copias/excluir";
        $result = $this->_doAuthenticatedPostRequest($url, $postData);
                
        return $result;
    }
    
    /**
     * Carrega a Cópia de Processo à partir de um hash
     * 
     * @param array $hash
     * @return Spu_Entity_CopiaProcesso
     */
    public function loadFromHash($hash)
    {
        $copia = new Spu_Entity_CopiaProcesso($this->getTicket());
        
        $copia->setNodeRef($this->_getHashValue($hash, 'noderef'));
        $copia->setNome($this->_getHashValue($hash, 'nome'));
        $copia->setProcesso($this->_loadProcessoFromHash($this->_getHashValue($hash, 'Processo')));
        
        return $copia;
    }
    
    /**
     * Carrega o Processo originador da Cópia à partir de um hash
     * 
     * @param array $hash
     * @return Spu_Entity_Processo
     */
    protected function _loadProcessoFromHash($hash)
    {
        $hashProcesso = array_pop($hash);
        $hashProcesso = array_pop($hashProcesso);
        $hashDadosProcesso = array_pop($hashProcesso);
        $processoService = new Spu_Service_Processo();
        $processo = $processoService->loadFromHash($hashDadosProcesso);
        return $processo;
    }
    
    /**
     * Carrega várias Cópias de Processo à partir de um hash
     * 
     * @param array $hashCopias
     * @return Spu_Entity_CopiaProcesso[]
     */
    protected function _loadManyFromHash($hashCopias)
    {
        $copias = array();
        foreach ($hashCopias as $hashCopia) {
            $hashCopia = array_pop($hashCopia);
            $copias[] = $this->loadFromHash($hashCopia);
        }
        
        return $copias;
    }
}