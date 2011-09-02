<?php
require_once('Arquivo.php');
class Anexo extends Arquivo
{
	protected $_processo;
	
	public function getProcesso()
	{
		return $this->_processo;
	}
	
	public function setProcesso(Processo $processo)
	{
		$this->_processo = $processo;
	}
}