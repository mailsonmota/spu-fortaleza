<?php
require_once('BaseDao.php');
class TipoTramitacaoDao extends BaseDao
{
	private $_baseUrl = 'spu/tiposprocesso';
	private $_ticketUrl = 'ticket';
	
	public function fetchAll()
	{
	    $url = $this->getBaseUrl() . "/" . $this->_baseUrl . "/tramitacoes/listar";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        $resultJson = $curlObj->doGetRequest($url);
        $result = json_decode($resultJson, true);
        
        return $result['Tramitacoes'][0];
	}
}