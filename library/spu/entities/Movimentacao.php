<?php
require_once('BaseAlfrescoEntity.php');
require_once('Protocolo.php');
require_once('Prioridade.php');
require_once('Usuario.php');
class Movimentacao extends BaseEntity
{
    protected $_data;
    protected $_hora;
    protected $_de;
    protected $_para;
    protected $_prazo;
    protected $_prioridade;
    protected $_despacho;
    protected $_usuario;
    protected $_tipo;
    
    const ABERTURA = 'ABERTURA';
    const RECEBIMENTO = 'RECEBIMENTO';
    const ENCAMINHAMENTO = 'ENCAMINHAMENTO';
    const DESPACHO = 'DESPACHO';
    const CANCELAMENTOENVIO = 'CANCELAMENTOENVIO';
    
    public function getData()
    {
    	return $this->_data;
    }
    
    public function setData($value)
    {
    	$this->_data = $value;
    }
    
	public function getHora()
    {
    	return $this->_hora;
    }
    
    public function setHora($value)
    {
    	$this->_hora = $value;
    }
    
    public function getDe()
    {
    	return $this->_de;
    }
    
    public function setDe($value)
    {
    	$this->_de = $value;
    }
    
    public function getPara()
    {
    	return $this->_para;
    }
    
    public function setPara($value)
    {
    	$this->_para = $value;
    }
    
    public function getPrazo()
    {
    	return $this->_prazo;
    }
    
    public function setPrazo($value)
    {
    	$this->_prazo = $value;
    }
    
    public function getPrioridade()
    {
    	return $this->_prioridade;
    }
    
    public function setPrioridade($value)
    {
    	$this->_prioridade = $value;
    }
    
    public function getDespacho()
    {
    	return $this->_despacho;
    }
    
    public function setDespacho($value)
    {
    	$this->_despacho = $value;
    }
    
	public function getUsuario()
    {
    	return $this->_usuario;
    }
    
    public function setUsuario($value)
    {
    	$this->_usuario = $value;
    }
    
	public function getTipo()
    {
    	return $this->_tipo;
    }
    
    public function setTipo($value)
    {
    	$this->_tipo = $value;
    }
    
    public function getDescricao()
    {
    	$tipo = $this->getTipo();
    	$descricao = '';
    	
    	switch ($tipo) {
    		case self::ABERTURA:
    			$descricao = $this->_getDescricaoAbertura();
    			$descricao = $this->_anexarDespachoDescricao($descricao);
    			break;
    		case self::RECEBIMENTO:
    			$descricao = $this->_getDescricaoRecebimento();
    			break;
    		case self::ENCAMINHAMENTO:
    			$descricao = $this->_getDescricaoEncaminhamento();
    			$descricao = $this->_anexarDespachoDescricao($descricao);
    			break;
    		case self::CANCELAMENTOENVIO:
    			$descricao = $this->_getDescricaoCancelamentoEnvio();
    			break;
    		case self::DESPACHO:
    			$descricao = $this->_getDescricaoDespacho();
    			$descricao = $this->_anexarDespachoDescricao($descricao);
    			break;
    	}
    	
    	return $descricao;
    }
    
    protected function _anexarDespachoDescricao($descricao)
    {
    	$descricao = ($descricao && $this->getDespacho()) ? 
    		$descricao . "<div class=\"comentario\">
    						<blockquote>" . $this->getUsuario()->nomeCompleto . ": <em>" . $this->getDespacho() . "</em></blockquote></div>" : 
    		$descricao;
    	return $descricao;	
    }
    
    protected function _getDescricaoAbertura()
    {
    	$nomeOrigem = $this->_getNomeOrigemParaDescricao();
    	$nomeDestino = $this->_getNomeDestinoParaDescricao();
    	
    	return "$nomeOrigem <em>abriu</em> o processo para $nomeDestino.";
    }
    
	protected function _getDescricaoRecebimento()
    {
    	$nomeOrigem = $this->_getNomeOrigemParaDescricao();
    	
    	return "$nomeOrigem <em>recebeu</em> o processo.";
    }
    
	protected function _getDescricaoEncaminhamento()
    {
    	$nomeOrigem = $this->_getNomeOrigemParaDescricao();
    	$nomeDestino = $this->_getNomeDestinoParaDescricao();
    	
    	return "$nomeOrigem <em>encaminhou</em> o processo para $nomeDestino.";
    }
    
	protected function _getDescricaoCancelamentoEnvio()
    {
    	$nomeDestino = $this->_getNomeDestinoParaDescricao();
    	
    	return "$nomeDestino <em>cancelou</em> o envio do processo.";
    }
    
	protected function _getDescricaoDespacho()
    {
    	$nomeOrigem = $this->_getNomeOrigemParaDescricao();
    	
    	return "$nomeOrigem criou um novo <em>despacho</em>:";
    }
    
    protected function _getNomeOrigemParaDescricao()
    {
    	$origem = $this->de;
    	return $this->_getNomeProtocoloParaDescricao($origem);
    }
    
	protected function _getNomeDestinoParaDescricao()
    {
    	$destino = $this->para;
    	return $this->_getNomeProtocoloParaDescricao($destino);
    }
    
    protected function _getNomeProtocoloParaDescricao(Protocolo $protocolo)
    {
    	$nome = '<b>' . $protocolo->getNome() . '</b>';
    	return $nome;
    }
    
	public function loadFromHash($hash)
    {
        $this->setData($this->_getHashValue($hash, 'data'));
        $this->setHora($this->_getHashValue($hash, 'hora'));
        $this->setDe($this->_loadProtocoloFromHash($this->_getHashValue($hash, 'de')));
        $this->setPara($this->_loadProtocoloFromHash($this->_getHashValue($hash, 'para')));
        $this->setPrazo($this->_getHashValue($hash, 'prazo'));
        $this->setPrioridade($this->_loadPrioridadeFromHash($this->_getHashValue($hash, 'prioridade')));
        $this->setDespacho($this->_getHashValue($hash, 'despacho'));
        $this->setUsuario($this->_loadUsuarioFromHash($this->_getHashValue($hash, 'usuario')));
        $this->setTipo($this->_getHashValue($hash, 'tipo'));
    }
    
	protected function _loadProtocoloFromHash($hash)
    {
    	$hash = array_pop($hash);
        $protocolo = new Protocolo();
        $protocolo->loadFromHash($hash);
        
        return $protocolo;
    }
    
	protected function _loadPrioridadeFromHash($hash)
    {
    	$hash = array_pop($hash);
        $prioridade = new Prioridade();
        $prioridade->loadFromHash($hash);
        
        return $prioridade;
    }
    
    protected function _loadUsuarioFromHash($hash)
    {
    	$hash = array_pop($hash);
        $usuario = new Usuario();
        $usuario->setNome($this->_getHashValue($hash, 'nome'));
        $usuario->setSobrenome($this->_getHashValue($hash, 'sobrenome'));
        $usuario->setEmail($this->_getHashValue($hash, 'email'));
        $usuario->setLogin($this->_getHashValue($hash, 'usuario'));
        
        return $usuario;
    }
}
?>