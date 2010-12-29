<?php
require_once('BaseDao.php');

class ArquivoDao extends BaseDao
{
    private $_processoBaseUrl = 'spu/processo';
    
    public function getArquivos($nodeUuid)
    {
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/arquivos/get/$nodeUuid";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        $resultJson = $curlObj->doGetRequest($url);
        $result = json_decode($resultJson, true);
        
        if ($this->isAlfrescoError($result)) {
            throw new Exception($this->getAlfrescoErrorMessage($result));
        }
        
        return $result;
    }

    /**
     * Estrutura de $postData
     * $data['processoId']
     * $data['fileContent']
     */
    public function uploadArquivo($postData)
    {
        // TODO Revisar web script "uploadarquivo"
        $url = $this->getBaseUrl() . "/" . $this->_processoBaseUrl . "/uploadarquivo";
        $url = $this->addAlfTicketUrl($url);
        
        $curlObj = new CurlClient();
        
        $result = $curlObj->doPostRequest($url, $postData, 'formdata');
        
        if ($this->isAlfrescoError($result)) {
            throw new Exception($this->getAlfrescoErrorMessage($result));
        }
        
        return $result;
    }
}