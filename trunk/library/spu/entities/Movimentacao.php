<?php
require_once('BaseAlfrescoEntity.php');
require_once('Protocolo.php');
require_once('Prioridade.php');
class Movimentacao extends BaseEntity
{
    protected $_data;
    protected $_de;
    protected $_para;
    protected $_prazo;
    protected $_prioridade;
    protected $_despacho;
    
    public function getData()
    {
    	return $this->_data;
    }
    
    public function setData($value)
    {
    	$this->_data = $value;
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
    
	public function loadFromHash($hash)
    {
        $this->setData($this->_getHashValue($hash, 'data'));
        $this->setDe($this->_loadProtocoloFromHash($this->_getHashValue($hash, 'de')));
        $this->setPara($this->_loadProtocoloFromHash($this->_getHashValue($hash, 'para')));
        $this->setPrazo($this->_getHashValue($hash, 'prazo'));
        $this->setPrioridade($this->_loadPrioridadeFromHash($this->_getHashValue($hash, 'prioridade')));
        $this->setDespacho($this->_getHashValue($hash, 'despacho'));
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
}
?>