<?php
require_once('../library/Alfresco/API/AlfrescoTiposProcesso.php');
require_once('BaseAlfrescoEntity.php');
require_once('Assunto.php');
class TipoProcesso extends BaseAlfrescoEntity
{
    protected $_nodeRef;
    protected $_nome;
    protected $_titulo;
    protected $_categoriaMaeDosAssuntos;
    protected $_simples;
    protected $_letra;
    protected $_tramitacao;
    protected $_abrangencia;
    protected $_observacao;
    protected $_envolvidoSigiloso;
    protected $_tiposManifestante;

	public function getNodeRef()
    {
        return $this->_nodeRef;
    }
    
    public function setNodeRef($nodeRef)
    {
        $this->_nodeRef = $nodeRef;
    }
    
    public function getNome()
    {
        return $this->_nome;
    }
    
    public function setNome($nome)
    {
        $this->_nome = $nome;
    }
    
    public function getTitulo()
    {
        return $this->_titulo;
    }
    
    public function setTitulo($titulo)
    {
        $this->_titulo = $titulo;
    }
    
    public function getSimples()
    {
        return $this->_simples;
    }
    
    public function setSimples($value)
    {
        $this->_simples = $value;
    }
    
    public function getLetra()
    {
        return $this->_letra;
    }
    
    public function setLetra($value)
    {
        $this->_letra = $value;
    }

	public function getTramitacao()
    {
        return $this->_tramitacao;
    }
    
    public function setTramitacao($value)
    {
        $this->_tramitacao = $value;
    }

	public function getAbrangencia()
    {
        return $this->_abrangencia;
    }
    
    public function setAbrangencia($value)
    {
        $this->_abrangencia = $value;
    }

	public function getObservacao()
    {
        return $this->_observacao;
    }
    
    public function setObservacao($value)
    {
        $this->_observacao = $value;
    }

	public function getEnvolvidoSigiloso()
    {
        return $this->_envolvidoSigiloso;
    }

    public function setEnvolvidoSigiloso($value)
    {
        $this->_envolvidoSigiloso = $value;
    }
    
    public function getTiposManifestante()
    {
        return $this->_tiposManifestante;
    }
    
    public function setTiposManifestante($value)
    {
        $this->_tiposManifestante = $value;
    }

	public function getId()
    {
        $nodeRef = $this->getNodeRef();
        return substr($nodeRef, strrpos($nodeRef, '/') + 1);
    }
    
    public function getAssuntos()
    {
        $assunto = new Assunto($this->_getTicket());
        return $assunto->listarPorTipoProcesso($this->getNome());
    }
    
    public function listar()
    {
        $service = new AlfrescoTiposProcesso(self::ALFRESCO_URL, $this->_getTicket());
        $hashDeTiposProcesso = $service->getTiposProcesso();
        
        $tiposProcesso = array();
        foreach ($hashDeTiposProcesso as $hashTipoProcesso) {
            $hashDadosTipoProcesso = array_pop($hashTipoProcesso); 
            $tipoProcesso = new TipoProcesso($this->_getTicket());
            $tipoProcesso->_loadTipoProcessoFromHash($hashDadosTipoProcesso);
            $tiposProcesso[] = $tipoProcesso;
        }
        
        return $tiposProcesso;
    }
    
    protected function _loadTipoProcessoFromHash($hash)
    {
        $this->setNodeRef($this->_getHashValue($hash, 'noderef'));
        $this->setNome($this->_getHashValue($hash, 'nome'));
        $this->setSimples(($this->_getHashValue($hash, 'simples') == '1') ? true : false);
        $this->setTitulo($this->_getHashValue($hash, 'titulo'));
        $this->setLetra($this->_getHashValue($hash, 'letra'));
        $this->setTramitacao($this->_getHashValue($hash, 'tramitacao'));
        $this->setAbrangencia($this->_getHashValue($hash, 'abrangencia'));
        $this->setObservacao($this->_getHashValue($hash, 'observacao'));
        $this->setEnvolvidoSigiloso(($this->_getHashValue($hash, 'envolvidoSigiloso') == '1') ? true : false);
        $this->setTiposManifestante($this->_getHashValue($hash, 'tiposManifestante'));
    }
    
    public function carregarPeloId($id)
    {
        $service = new AlfrescoTiposProcesso(self::ALFRESCO_URL, $this->_getTicket());
        $hashDeTiposProcesso = $service->getTipoProcesso($id);
        
        foreach ($hashDeTiposProcesso as $hashTipoProcesso) {
            $hashDadosTipoProcesso = array_pop($hashTipoProcesso); 
            $this->_loadTipoProcessoFromHash($hashDadosTipoProcesso);
        }
    }
}
?>