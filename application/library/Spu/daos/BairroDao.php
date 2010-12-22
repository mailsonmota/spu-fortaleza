<?php
require_once('BaseDao.php');
class BairroDao extends BaseDao
{
	private $_bairrosBaseUrl = 'spu/bairros';
	private $_bairrosTicketUrl = 'ticket';
	
	public function getBairros()
	{
	    $url = $this->getBaseUrl() . "/" . $this->_bairrosBaseUrl . "/listar";
	    $url = $this->addAlfTicketUrl($url);
	    
	    $curlObj = new CurlClient();
        $resultJson = $curlObj->doGetRequest($url);
        $result = json_decode($resultJson, true);
        
        return $result['Bairros'];
	}
}