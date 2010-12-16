<?php
require_once('BaseClassification.php');
Loader::loadDao('StatusDao');
class Status extends BaseClassification
{
	const ARQUIVADO = 'Arquivado';
	
	protected function _getDao()
	{
		$dao = new StatusDao(self::ALFRESCO_URL, $this->_getTicket());
		return $dao;
	}
}
?>