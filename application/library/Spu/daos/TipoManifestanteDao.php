<?php
require_once('BaseDao.php');
class TipoManifestanteDao extends BaseDao
{
	private $_baseUrl = 'spu/tiposprocesso';
	private $_ticketUrl = 'ticket';
	
	public function fetchAll()
    {
        $url = $this->getBaseUrl() . "/" . $this->_baseUrl . "/tiposmanifestante/listar";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        $resultJson = $curlObj->doGetRequest($url);
        $result = json_decode($resultJson, true);
        
        return $result['Tipos de Manifestante'][0];
    }
}