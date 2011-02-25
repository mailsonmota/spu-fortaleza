<?php
require_once('Base.php');
Loader::loadClassification('StatusArquivamento');
class Arquivamento extends Spu_Aspect_Base
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