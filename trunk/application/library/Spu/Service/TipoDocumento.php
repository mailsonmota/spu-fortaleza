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
    
    /**
     * Retorna os Tipos de Documentos cadastrados no SPU
     * 
     * @return Spu_Entity_Classification_TipoDocumento[]
     */
    public function getTiposDocumentos()
    {
        $url = $this->getBaseUrl() . "/" . $this->_tiposDocumentosBaseUrl . "/listar";
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

