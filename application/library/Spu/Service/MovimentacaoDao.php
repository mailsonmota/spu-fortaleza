<?php
Loader::loadAspect('Movimentacao');
class MovimentacaoDao extends BaseDao
{
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
    
    public function loadFromHash($hash)
    {
    	$movimentacao = new Movimentacao();
        
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
    
    protected function _loadUsuarioFromHash($hash)
    {
        $usuario = new Usuario();
        if ($hash) {
            $hash = array_pop($hash);
            $usuario->setNome($this->_getHashValue($hash, 'nome'));
            $usuario->setSobrenome($this->_getHashValue($hash, 'sobrenome'));
            $usuario->setEmail($this->_getHashValue($hash, 'email'));
            $usuario->setLogin($this->_getHashValue($hash, 'usuario'));
        }
        
        return $usuario;
    }
    
    protected function _loadProtocoloFromHash($hash)
    {
        if ($hash) {
	    	$hash = array_pop($hash);
	        $protocoloDao = new ProtocoloDao($this->getTicket());
	        $protocolo = $protocoloDao->loadFromHash($hash);
        } else {
        	$protocolo = new Protocolo();
        }
        
        return $protocolo;
    }
    
    protected function _loadPrioridadeFromHash($hash)
    {
        $hash = array_pop($hash);
        $prioridadeDao = new PrioridadeDao($this->getTicket());
        $prioridade = $prioridadeDao->loadFromHash($hash);
        
        return $prioridade;
    }
}