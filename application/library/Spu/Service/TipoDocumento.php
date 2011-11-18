<?php
/**
 * Classe para acessar os serviços de Tipo de Documento do SPU
 * 
 * @package SPU
 * @see Spu_Service_Abstract
 */
class Spu_Service_TipoDocumento extends Spu_Service_Abstract
{
    /**
	 * URL Base dos serviços (a ser acrescentada à url dos serviços do Alfresco)
	 * @var string
	 */
    private $_tiposDocumentosBaseUrl = 'spu/tipos-documentos';
    
    public function getTipoDocumento($nodeRef)
    {
        $url = $this->getBaseUrl() . "/" . $this->_tiposDocumentosBaseUrl . "/get/$nodeRef";
        $result = $this->_doAuthenticatedGetRequest($url);

        return $this->loadFromHash($result['Tipos de Documentos'][0]);
    }

    /**
     * Por padrão, retorna os Tipos de Documentos de primeiro nível cadastrados no SPU.
     * Pode receber um nodeRef ("workspace://SpacesStore/123-123-123-123"). Nesse caso, retorna
     * os Tipos de Documentos imediatamente abaixo daquele nodeRef.
     * 
     * @return Spu_Entity_Classification_TipoDocumento[]
     */
    public function getTiposDocumentos($parentNodeRef = null)
    {
        $url = $this->getBaseUrl() . "/" . $this->_tiposDocumentosBaseUrl . "/listar";

        if ($parentNodeRef) {
            $url .= '?parent-noderef=' . $parentNodeRef;
        }

        $result = $this->_doAuthenticatedGetRequest($url);
        
        return $this->_loadManyFromHash($result['Tipos de Documentos']);
    }

    public function buscar($term, $nivel = 3)
    {
        $url = $this->getBaseUrl() . "/" . $this->_tiposDocumentosBaseUrl
            . "/buscar/" . $term . '/' . $nivel;

        $result = $this->_doAuthenticatedGetRequest($url);

        return $this->_loadManyFromHash($result['Tipos de Documentos']);
    }

    /**
     * Carrega o Tipo de Documento através de um hash
     * 
     * @param array $hash
     * @return Spu_Entity_Classification_TipoDocumento
     */
    public function loadFromHash($hash)
    {
        $tipoDocumento = new Spu_Entity_Classification_TipoDocumento();

        $tipoDocumento->setNodeRef($this->_getHashValue($hash, 'noderef'));
        $tipoDocumento->setNome($this->_getHashValue($hash, 'nome'));
        $tipoDocumento->setDescricao($this->_getHashValue($hash, 'descricao'));
        $tipoDocumento->setParent($this->_getHashValue($hash, 'parent'));
        $tipoDocumento->setParentRaiz($this->_getHashValue($hash, 'parentRaiz'));
        
        return $tipoDocumento;
    }
    
    /**
     * Carrega vários Tipos de Documentos através de um hash
     * 
     * @param array $hash
     * @return Spu_Entity_Classification_TipoDocumento[]
     */
    protected function _loadManyFromHash($hash)
    {
        $tiposDocumentos = array();
        foreach ($hash[0] as $hashTipoDocumento) {
            $tiposDocumentos[] = $this->loadFromHash($hashTipoDocumento[0]);
        }
        
        return $tiposDocumentos;
    }
}

