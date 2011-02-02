<?php
require_once('BaseDao.php');
class CopiaProcessoDao extends BaseDao
{
    private $_processoBaseUrl = 'spu/processo';
    private $_processoTicketUrl = 'ticket';
    
    public function getCopias($offset, $pageSize, $filter)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/copias/$offset/$pageSize/$filter";
        $url = $this->addAlfTicketUrl($url);
        
        return $this->_getCopiasFromUrl($url);
    }
    
    protected function _getCopiasFromUrl($url)
    {
        $result = $this->_getResultFromUrl($url);
        return $result['Copias'][0];
    }
    
    public function deleteAll($postData)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/copias/excluir";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        
        $result = $curlObj->doPostRequest($url, $postData);
        
        if ($this->isAlfrescoError($result)) {
            throw new Exception($this->getAlfrescoErrorMessage($result));
        }
        
        return $result;
    }
}