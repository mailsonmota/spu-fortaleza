<?php
class Spu_Service_Arquivamento extends Spu_Service_Abstract
{
    public function loadFromHash($hash)
    {
        $arquivamento = new Spu_Entity_Aspect_Arquivamento();
        
        $arquivamento->setStatus($this->loadStatusFromHash($this->_getHashValue($hash, 'status')));
        $arquivamento->setMotivo($this->_getHashValue($hash, 'motivo'));
        $arquivamento->setLocal($this->_getHashValue($hash, 'local'));
        $arquivamento->setPasta($this->_getHashValue($hash, 'pasta'));
        
        return $arquivamento;
    }
    
    public function loadStatusFromHash($hash)
    {
        $hash = array_pop($hash);
        $statusArquivamentoService = new Spu_Service_StatusArquivamento();
        $status = $statusArquivamentoService->loadFromHash($hash);
        
        return $status;
    }
}