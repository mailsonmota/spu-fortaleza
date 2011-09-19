<?php
class Spu_Service_TipoProcesso extends Spu_Service_Abstract
{
    private $_tiposProcessoBaseUrl = 'spu/tiposprocesso';
    private $_tiposProcessoTicketUrl = 'ticket';
    
    public function getTiposProcesso($origem = null)
    {
    	$url = $this->getBaseUrl() . "/" . $this->_tiposProcessoBaseUrl . "/listar";
        
    	if ($origem) {
    		$url .= "/$origem";
    	}
    	
    	$result = $this->_doAuthenticatedGetRequest($url);
        
        return $this->_loadManyFromHash($result['Tipos de Processo'][0]);
    }
    
    public function getTipoProcesso($nodeUuid)
    {
        $url = $this->getBaseUrl() . "/" . $this->_tiposProcessoBaseUrl . "/get/$nodeUuid";
        
        $result = $this->_doAuthenticatedGetRequest($url);
        
        return $this->loadFromHash(array_pop(array_pop($result['Tipo de Processo'][0])));
    }
    
    public function loadFromHash($hash)
    {
        $tipoProcesso = new Spu_Entity_TipoProcesso();
        
        $tipoProcesso->setNodeRef($this->_getHashValue($hash, 'noderef'));
        $tipoProcesso->setNome($this->_getHashValue($hash, 'nome'));
        $tipoProcesso->setSimples(($this->_getHashValue($hash, 'simples') == '1') ? true : false);
        $tipoProcesso->setTitulo($this->_getHashValue($hash, 'titulo'));
        $tipoProcesso->setLetra($this->_getHashValue($hash, 'letra'));
        $tipoProcesso->setObservacao($this->_getHashValue($hash, 'observacao'));
        $tipoProcesso->setEnvolvidoSigiloso(($this->_getHashValue($hash, 'envolvidoSigiloso') == '1') ? true : false);
        $tipoProcesso->setTiposManifestante($this->_getHashValue($hash, 'tiposManifestante'));
        $tipoProcesso->setTramitacao($this->_loadTipoTramitacaoFromHash($hash));
        $tipoProcesso->setAbrangencia($this->_loadTipoAbrangenciaFromHash($hash));
        $tipoProcesso->setTiposManifestante($this->_loadTiposManifestanteFromHash($hash));
        
        return $tipoProcesso;
    }
    
    protected function _loadTipoTramitacaoFromHash($hash)
    {
        $hashTramitacao = $this->_getHashValue($hash, 'tramitacao');
        $tramitacao = new Spu_Entity_Classification_TipoTramitacao($this->getTicket());
        if ($hashTramitacao) {
        	$service = new Spu_Service_TipoTramitacao();
        	$tramitacao = $service->loadFromHash(array_pop($hashTramitacao));
        }
        return $tramitacao;
    }
    
    protected function _loadTipoAbrangenciaFromHash($hash)
    {
        $hashAbrangencia = $this->_getHashValue($hash, 'abrangencia');
        $abrangencia = new Spu_Entity_Classification_TipoAbrangencia($this->getTicket());
        if ($hashAbrangencia) {
        	$service = new Spu_Service_TipoAbrangencia();
        	$abrangencia = $service->loadFromHash(array_pop($hashAbrangencia));
        }
        return $abrangencia;
    }
    
    protected function _loadTiposManifestanteFromHash($hash)
    {
        $hashTiposManifestante = $this->_getHashValue($hash, 'tiposManifestante');
        $tiposManifestante = array();
        if ($hashTiposManifestante) {
            $hashTiposManifestante = array_pop($hashTiposManifestante);
            foreach ($hashTiposManifestante as $hashTipoManifestante) {
            	$service = new Spu_Service_TipoManifestante();
            	$tiposManifestante[] = $service->loadFromHash(array_pop($hashTipoManifestante));
            }         
        }
        return $tiposManifestante;
    }
    
    protected function _loadManyFromHash($hash)
    {
        $tiposProcesso = array();
		if ($hash) {
	        foreach ($hash as $hashTipoProcesso) {
	            $hashTipoProcesso = array_pop($hashTipoProcesso);
	            $tiposProcesso[] = $this->loadFromHash($hashTipoProcesso);
	        }
		}

        return $tiposProcesso;
    }
}