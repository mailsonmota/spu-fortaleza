<?php
/**
 * Classe para acessar os serviços de Arquivamento do SPU
 * 
 * @author Bruno Cavalcante <brunofcavalcante@gmail.com>
 * @package SPU
 * @see Spu_Service_Abstract
 */
class Spu_Service_Arquivamento extends Spu_Service_Abstract
{
    /**
	 * Carrega o Aspect de Arquivamento à partir de um hash
	 * 
	 * @param array $hash
	 * @return Spu_Entity_Aspect_Arquivamento
	 */
    public function loadFromHash($hash)
    {
        $arquivamento = new Spu_Entity_Aspect_Arquivamento();
        
        $arquivamento->setStatus($this->loadStatusFromHash($this->_getHashValue($hash, 'status')));
        $arquivamento->setMotivo($this->_getHashValue($hash, 'motivo'));
        $arquivamento->setLocal($this->_getHashValue($hash, 'local'));
        $arquivamento->setPasta($this->_getHashValue($hash, 'pasta'));
        $arquivamento->setEstante($this->_getHashValue($hash, 'estante'));
        $arquivamento->setPrateleira($this->_getHashValue($hash, 'prateleira'));
        $arquivamento->setCaixa($this->_getHashValue($hash, 'caixa'));
        $arquivamento->setArquivo($this->_getHashValue($hash, 'arquivo'));
        
        return $arquivamento;
    }
    
    /**
     * Carrega o Status de Arquivamento à partir de um hash
     * 
     * @param array $hash
     * @return Spu_Entity_Classification_StatusArquivamento
     */
    public function loadStatusFromHash($hash)
    {
        $hash = array_pop($hash);
        $statusArquivamentoService = new Spu_Service_StatusArquivamento();
        $status = $statusArquivamentoService->loadFromHash($hash);
        
        return $status;
    }
}