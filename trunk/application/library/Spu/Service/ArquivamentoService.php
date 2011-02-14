<?php
Loader::loadAspect('Arquivamento');
Loader::loadService('StatusArquivamentoService');
class ArquivamentoService extends BaseService
{
    public function loadFromHash($hash)
    {
        $arquivamento = new Arquivamento();
        
        $arquivamento->setStatus($this->loadStatusFromHash($this->_getHashValue($hash, 'status')));
        $arquivamento->setMotivo($this->_getHashValue($hash, 'motivo'));
        $arquivamento->setLocal($this->_getHashValue($hash, 'local'));
        $arquivamento->setPasta($this->_getHashValue($hash, 'pasta'));
        
        return $arquivamento;
    }
    
    public function loadStatusFromHash($hash)
    {
        $hash = array_pop($hash);
        $statusArquivamentoService = new StatusArquivamentoService();
        $status = $statusArquivamentoService->loadFromHash($hash);
        
        return $status;
    }
}