<?php
require_once('BaseDao.php');
class TipoProcessoDao extends BaseDao
{
    private $_tiposProcessoBaseUrl = 'spu/tiposprocesso';
    private $_tiposProcessoTicketUrl = 'ticket';
    
    public function getTiposProcesso()
    {
        $url = $this->getBaseUrl() . "/" . $this->_tiposProcessoBaseUrl . "/listar";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        $resultJson = $curlObj->doGetRequest($url);
        $result = json_decode($resultJson, true);
        
        return $result['Tipos de Processo'][0];
    }
    
    public function getTipoProcesso($nodeUuid)
    {
        $url = $this->getBaseUrl() . "/" . $this->_tiposProcessoBaseUrl . "/get/$nodeUuid";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        $resultJson = $curlObj->doGetRequest($url);
        $result = json_decode($resultJson, true);
        
        return $result['Tipo de Processo'][0];
    }
}