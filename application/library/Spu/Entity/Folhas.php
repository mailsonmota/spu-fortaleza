<?php
class Spu_Entity_Folhas extends Spu_Entity_Abstract
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