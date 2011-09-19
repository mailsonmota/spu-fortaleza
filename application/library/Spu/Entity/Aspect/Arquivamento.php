<?php
class Spu_Entity_Aspect_Arquivamento extends Spu_Entity_Aspect_Abstract
{
    protected $_status;
    protected $_motivo;
    protected $_local;
    protected $_pasta;
    
    public function getStatus() {
        return $this->_status;
    }
    
    public function setStatus($value)
    {
        $this->_status = $value;
    }
    
    public function getMotivo() {
        return $this->_motivo;
    }
    
    public function setMotivo($value)
    {
        $this->_motivo = $value;
    }
    
    public function getLocal() {
        return $this->_local;
    }
    
    public function setLocal($value)
    {
        $this->_local = $value;
    }
    
    public function getPasta() {
        return $this->_pasta;
    }
    
    public function setPasta($value)
    {
        $this->_pasta = $value;
    }
}