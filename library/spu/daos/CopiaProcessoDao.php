<?php
require_once('BaseDao.php');
class CopiaProcessoDao extends BaseDao
{
	private $_processoBaseUrl = 'spu/processo';
	private $_processoTicketUrl = 'ticket';
	
	public function getCopias()
	{
		$url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/copias";
        $url = $this->addAlfTicketUrl($url);
        
        return $this->_getCopiasFromUrl($url);
	}
	
	protected function _getCopiasFromUrl($url)
	{
		$result = $this->_getResultFromUrl($url);
		return $result['Copias'][0];
	}
}