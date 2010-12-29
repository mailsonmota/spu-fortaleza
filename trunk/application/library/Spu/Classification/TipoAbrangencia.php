<?php
require_once('BaseClassification.php');
Loader::loadDao('TipoAbrangenciaDao');
class TipoAbrangencia extends BaseClassification
{
    public function listar()
    {
        $dao = $this->_getDao();
        $hashDeTipoAbrangencia = $dao->fetchAll();
        
        $tiposAbrangencia = array();
        foreach ($hashDeTipoAbrangencia as $hashTipoAbrangencia) {
            $tipoAbrangencia = new TipoAbrangencia($this->_getTicket());
            $tipoAbrangencia->loadFromHash($hashTipoAbrangencia[0]);
            $tiposAbrangencia[] = $tipoAbrangencia;
        }
        
        return $tiposAbrangencia;
    }
    
    protected function _getDao()
    {
        $dao = new TipoAbrangenciaDao($this->_getTicket());
        return $dao;
    }
}