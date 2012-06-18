<?php
/**
 * Classe para acessar os serviços de Tipo de Manifestante dos Processos do SPU
 *
 * @author Bruno Cavalcante <brunofcavalcante@gmail.com>
 * @package SPU
 * @see Spu_Service_Abstract
 */
class Spu_Service_TipoManifestante extends Spu_Service_Abstract
{
    /**
	 * URL Base dos serviços (a ser acrescentada à url dos serviços do Alfresco)
	 * @var string
	 */
    private $_baseUrl = 'spu/tiposprocesso';
    
    /**
     * Retorna todas as opções de tipo de manifestante
     *
     * @return Spu_Entity_Classification_TipoManifestante[]
     */
    public function fetchAll()
    {
        $url = $this->getBaseUrl() . "/" . $this->_baseUrl . "/tiposmanifestante/listar";
        
        $name = $this->getNameForMethod('fetchAll');
        if (($result = $this->getCache()->load($name)) === false) {

            $result = $this->_doAuthenticatedGetRequest($url);

            $this->getCache()->save($result, $name);
        }
        
        
        return $this->_loadManyFromHash($result['Tipos de Manifestante'][0]);
    }
    
    /**
     * Carrega o tipo de manifestante através de um hash
     *
     * @param array $hash
     * @return Spu_Entity_Classification_TipoManifestante
     */
    public function loadFromHash($hash)
    {
        $tipoManifestante = new Spu_Entity_Classification_TipoManifestante();
        
        $tipoManifestante->setNodeRef($this->_getHashValue($hash, 'noderef'));
        $tipoManifestante->setNome($this->_getHashValue($hash, 'nome'));
        $tipoManifestante->setDescricao($this->_getHashValue($hash, 'descricao'));
        
        return $tipoManifestante;
    }
    
    /**
     * Carrega vários tipos de manifestante através de um hash
     * 
     * @param array $hash
     * @return Spu_Entity_Classification_TipoManifestante[]
     */
    public function _loadManyFromHash($hash)
    {
        $tiposManifestante = array();
        foreach ($hash as $hashTipoManifestante) {
            $tiposManifestante[] = $this->loadFromHash($hashTipoManifestante[0]);
        }
        
        return $tiposManifestante;
    }
}