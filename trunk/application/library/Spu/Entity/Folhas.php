<?php
require_once('BaseEntity.php');
class Folhas extends BaseEntity
{
    protected $_quantidade;
    protected $_volumes;
    
    public function getQuantidade()
    {
        return $this->_quantidade;
    }
    
    public function setQuantidade($data)
    {
        $this->_quantidade = $data;
    }
    
    public function getVolumes()
    {
        return $this->_volumes;
    }
    
    public function setVolumes($data)
    {
        $this->_volumes = $data;
    }
    
    public function addVolume($data)
    {
        $this->_volumes[] = $data;
    }
}