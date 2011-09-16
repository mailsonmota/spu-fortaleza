<?php
class Spu_Entity_Anexo extends Spu_Entity_Arquivo
{
	protected $_processo;
	
	public function getProcesso()
	{
		return $this->_processo;
	}
	
	public function setProcesso(Spu_Entity_Processo $processo)
	{
		$this->_processo = $processo;
	}
}