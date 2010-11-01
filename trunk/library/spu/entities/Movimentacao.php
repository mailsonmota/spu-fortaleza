<?php
require_once('BaseAlfrescoEntity.php');
class Movimentacao extends BaseEntity
{
    protected $_data;
    protected $_de;
    protected $_para;
    protected $_prazo;
    protected $_prioridade;
    
    
    
	public function loadFromHash($hash)
    {
        $this->setNodeRef($this->_getHashValue($hash, 'noderef'));
        $this->setNome($this->_getHashValue($hash, 'nome'));
        $this->setDescricao($this->_getHashValue($hash, 'descricao'));
    }
}
?>