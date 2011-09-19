<?php
/**
 * Representa os Anexos dos Processos do SPU
 * @author bruno <brunofcavalcante@gmail.com>
 * @package SPU
 * @see Spu_Entity_Arquivo
 */
class Spu_Entity_Anexo extends Spu_Entity_Arquivo
{
	protected $_processo;
	
	/**
	 * Retorna o Processo a qual o arquivo pertence
	 * @return Spu_Entity_Processo
	 */
	public function getProcesso()
	{
		return $this->_processo;
	}
	
	public function setProcesso(Spu_Entity_Processo $processo)
	{
		$this->_processo = $processo;
	}
}