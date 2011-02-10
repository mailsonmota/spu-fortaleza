<?php
require_once('BaseEntity.php');
Loader::loadDao('AssuntoDao');
Loader::loadDao('ArquivoDao');
class Assunto extends BaseEntity
{
	protected $_nodeRef;
	protected $_nome;
	protected $_corpo;
	protected $_notificarNaAbertura;
	protected $_tipoProcesso;
	
	public function getTipoProcesso()
	{
	    return $this->_tipoProcesso;
	}
	
	public function setTipoProcesso($value)
	{
	    $this->_tipoProcesso = $value;
	}

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

	public function getCorpo()
	{
		return $this->_corpo;
	}

	public function setCorpo($value)
	{
		$this->_corpo = $value;
	}

	public function getNotificarNaAbertura()
	{
		return $this->_notificarNaAbertura;
	}

	public function setNotificarNaAbertura($value)
	{
		$this->_notificarNaAbertura = $value;
	}

	public function getId()
	{
		$nodeRef = $this->getNodeRef();
		return substr($nodeRef, strrpos($nodeRef, '/') + 1);
	}
	
	//FIXME: Implementar Assunto::hasFormulario
	public function hasFormulario()
	{
		$hasFormulario = false;
		try {
		    $formularioXsd = $this->getFormularioXsd();
		    $hasFormulario = true;
		} catch (Exception $e) {
			$hasFormulario = false;
		}
		
		return $hasFormulario;
	}
}