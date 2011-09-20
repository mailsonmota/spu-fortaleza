<?php
/**
 * Classe para acessar os serviços de Movimentação de Processo do SPU
 * 
 * @author Bruno Cavalcante <brunofcavalcante@gmail.com>
 * @package SPU
 * @see Spu_Service_Abstract
 */
class Spu_Service_Movimentacao extends Spu_Service_Abstract
{
	/**
	 * Carrega várias Movimentações à partir de um hash
	 * 
	 * @param array $hash
	 * @return Spu_Entity_Aspect_Movimentacao[]
	 */
    public function loadManyFromHash($hash)
    {
        $movimentacoes = array();
        if ($hash) {
            foreach ($hash[0] as $hashMovimentacao) {
                $hashMovimentacao = array_pop($hashMovimentacao);
                $movimentacoes[] = $this->loadFromHash($hashMovimentacao);
            }
        }
        
        return $movimentacoes;
    }
    
    /**
     * Carrega uma Movimentação à partir de um hash
     * 
     * @param array $hash
     * @return Spu_Entity_Aspect_Movimentacao
     */
    public function loadFromHash($hash)
    {
        $movimentacao = new Spu_Entity_Aspect_Movimentacao();
        
        $movimentacao->setData($this->_getHashValue($hash, 'data'));
        $movimentacao->setHora($this->_getHashValue($hash, 'hora'));
        $movimentacao->setDe($this->_loadProtocoloFromHash($this->_getHashValue($hash, 'de')));
        $movimentacao->setPara($this->_loadProtocoloFromHash($this->_getHashValue($hash, 'para')));
        $movimentacao->setPrazo($this->_getHashValue($hash, 'prazo'));
        $movimentacao->setPrioridade($this->_loadPrioridadeFromHash($this->_getHashValue($hash, 'prioridade')));
        $movimentacao->setDespacho($this->_getHashValue($hash, 'despacho'));
        $movimentacao->setUsuario($this->_loadUsuarioFromHash($this->_getHashValue($hash, 'usuario')));
        $movimentacao->setTipo($this->_getHashValue($hash, 'tipo'));
        
        return $movimentacao;
    }
    
    /**
     * Carrega o usuário da Movimentação à partir de um hash
     * 
     * @param array $hash
     * @return Spu_Entity_Usuario
     */
    protected function _loadUsuarioFromHash($hash)
    {
        $usuario = new Spu_Entity_Usuario();
        if ($hash) {
            $hash = array_pop($hash);
            $usuario->setNome($this->_getHashValue($hash, 'nome'));
            $usuario->setSobrenome($this->_getHashValue($hash, 'sobrenome'));
            $usuario->setEmail($this->_getHashValue($hash, 'email'));
            $usuario->setLogin($this->_getHashValue($hash, 'usuario'));
        }
        
        return $usuario;
    }
    
    /**
     * Carrega o Protocolo da Movimentação à partir de um hash
     * 
     * @param array $hash
     * @return Spu_Entity_Protocolo
     */
    protected function _loadProtocoloFromHash($hash)
    {
        if ($hash) {
            $hash = array_pop($hash);
            $protocoloService = new Spu_Service_Protocolo($this->getTicket());
            $protocolo = $protocoloService->loadFromHash($hash);
        } else {
            $protocolo = new Spu_Entity_Protocolo();
        }
        
        return $protocolo;
    }
    
    /**
     * Carrega a Prioridade da Movimentação à partir de um hash
     * 
     * @param array $hash
     * @return Spu_Entity_Classification_Prioridade
     */
    protected function _loadPrioridadeFromHash($hash)
    {
        $hash = array_pop($hash);
        $prioridadeService = new Spu_Service_Prioridade($this->getTicket());
        $prioridade = $prioridadeService->loadFromHash($hash);
        
        return $prioridade;
    }
}