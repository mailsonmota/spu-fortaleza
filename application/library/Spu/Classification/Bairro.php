<?php
require_once('BaseClassification.php');
Loader::loadDao('BairroDao');
class Bairro extends BaseClassification
{
    public function listar()
    {
        $dao = $this->_getDao();
        $hashDeBairros = $dao->getBairros();
        
        $bairros = array();
        foreach ($hashDeBairros[0] as $hashBairro) {
            $bairro = new Bairro($this->_getTicket());
            $bairro->loadFromHash($hashBairro[0]);
            $bairros[] = $bairro;
        }
        
        return $bairros;
    }
    
    protected function _getDao()
    {
    	$dao = new BairroDao($this->_getTicket());
    	return $dao;
    }
}
?>