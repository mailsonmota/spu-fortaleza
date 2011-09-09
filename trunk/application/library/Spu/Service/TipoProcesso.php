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
    	
    	$url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        $result = $curlObj->doGetRequest($url);
        
        return $this->_loadManyFromHash($result['Tipos de Processo'][0]);
    }
    
    public function getTipoProcesso($nodeUuid)
    {
        $url = $this->getBaseUrl() . "/" . $this->_tiposProcessoBaseUrl . "/get/$nodeUuid";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        $result = $curlObj->doGetRequest($url);
        
        $hashTipoProcesso = $result['Tipo de Processo'][0];
        
        return $this->loadFromHash(array_pop(array_pop($hashTipoProcesso)));
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
            $hashTramitacao = array_pop($hashTramitacao);
            $tramitacao->setNodeRef($this->_getHashValue($hashTramitacao, 'noderef'));
            $tramitacao->setNome($this->_getHashValue($hashTramitacao, 'nome'));
            $tramitacao->setDescricao($this->_getHashValue($hashTramitacao, 'descricao'));            
        }
        return $tramitacao;
    }
    
    protected function _loadTipoAbrangenciaFromHash($hash)
    {
        $hashAbrangencia = $this->_getHashValue($hash, 'abrangencia');
        $abrangencia = new Spu_Entity_Classification_TipoAbrangencia($this->getTicket());
        if ($hashAbrangencia) {
            $hashAbrangencia = array_pop($hashAbrangencia);
            $abrangencia->setNodeRef($this->_getHashValue($hashAbrangencia, 'noderef'));
            $abrangencia->setNome($this->_getHashValue($hashAbrangencia, 'nome'));
            $abrangencia->setDescricao($this->_getHashValue($hashAbrangencia, 'descricao'));            
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
                $hashTipoManifestante = array_pop($hashTipoManifestante);
                $tipoManifestante = new Spu_Entity_Classification_TipoManifestante($this->getTicket());
                $tipoManifestante->setNodeRef($this->_getHashValue($hashTipoManifestante, 'noderef'));
                $tipoManifestante->setNome($this->_getHashValue($hashTipoManifestante, 'nome'));
                $tipoManifestante->setDescricao($this->_getHashValue($hashTipoManifestante, 'descricao'));
                $tiposManifestante[] = $tipoManifestante;
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