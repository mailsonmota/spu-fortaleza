<?php
require_once('BaseClassification.php');
Loader::loadDao('PrioridadeDao');
class Prioridade extends BaseClassification
{
    public function listar()
    {
        $dao = $this->_getDao();
        $hashDePrioridade = $dao->fetchAll();
        
        $prioridades = array();
        foreach ($hashDePrioridade as $hashPrioridade) {
            $prioridade = new Prioridade($this->_getTicket());
            $prioridade->loadFromHash($hashPrioridade[0]);
            $prioridades[] = $prioridade;
        }
        
        return $prioridades;
    }
    
    protected function _getDao()
    {
    	$dao = new PrioridadeDao($this->_getTicket());
    	return $dao;
    }
}
?>