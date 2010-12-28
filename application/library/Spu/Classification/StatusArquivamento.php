<?php
require_once('BaseClassification.php');
Loader::loadDao('StatusArquivamentoDao');
class StatusArquivamento extends BaseClassification
{
    public function listar()
    {
        $dao = $this->_getDao();
        $hashDeStatus = $dao->getStatusArquivamento();
        
        $arrayStatus = array();
        foreach ($hashDeStatus as $hashStatus) {
            $status = new StatusArquivamento($this->_getTicket());
            $status->loadFromHash($hashStatus[0]);
            $arrayStatus[] = $status;
        }
        
        return $arrayStatus;
    }
    
    protected function _getDao()
    {
        $dao = new StatusArquivamentoDao(self::ALFRESCO_URL, $this->_getTicket());
        return $dao;
    }
}