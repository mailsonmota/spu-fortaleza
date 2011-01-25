<?php
require_once('BaseEntity.php');
require_once('Grupo.php');
Loader::loadDao('UsuarioDao');
class Usuario extends BaseEntity
{
    protected $_nome;
    protected $_sobrenome;
    protected $_email;
    protected $_login;
    protected $_grupos;
    
    public function getNome() {
        return $this->_nome;
    }
    
    public function setNome($nome) {
        $this->_nome = $nome;
    }
    
    public function getSobrenome() {
        return $this->_sobrenome;
    }
    
    public function setSobrenome($sobrenome) {
        $this->_sobrenome = $sobrenome;
    }
    
    public function getEmail() {
        return $this->_email;
    }
    
    public function setEmail($email) {
        $this->_email = $email;
    }
    
    public function getLogin() {
        return $this->_login;
    }
    
    public function setLogin($login) {
        $this->_login = $login;
    }
    
    public function getGrupos() {
        return $this->_grupos;
    }
    
    public function setGrupos($grupos) {
        $this->_grupos = $grupos;
    }
    
    public function getNomeCompleto()
    {
        return $this->_nome . ' ' . $this->_sobrenome;
    }
    
    protected function _getDao() {
        $dao = new UsuarioDao($this->_getTicket());
        return $dao;
    }
    
    public function carregarPeloLogin($userName) {
        $dao = $this->_getDao();
        $hashDetalhesLogin = $dao->find($userName);
        $this->loadUsuarioFromHash($hashDetalhesLogin);
    }
    
    public function loadUsuarioFromHash($hashDetalhesLogin) {
        $this->setNome($hashDetalhesLogin['firstName']);
        $this->setSobrenome($hashDetalhesLogin['lastName']);
        $this->setEmail($hashDetalhesLogin['email']);
        $this->setLogin($hashDetalhesLogin['userName']);
    }
    
    public function loadGrupos() {
    	$dao = $this->_getDao();
        $hashGrupos = $dao->fetchGroups($this->_login);
        
        $grupos = array();
        if (count($hashGrupos) > 0) {
            foreach ($hashGrupos as $hashGrupo) {
                $grupo = new Grupo();
                $grupo->setNome($hashGrupo['item']);
                $grupos[] = $grupo;
            }
        }
        
        $this->setGrupos($grupos);
    }
    
    public function isAdministrador() {
    	if (!$this->_grupos) {
    		$this->loadGrupos();
    	}
    	
    	foreach ($this->_grupos as $grupo) {
    		if ($grupo->isAdministrador()) {
    			return true;
    		}
    	}
    	
    	return false;
    }
    
    public function isGuest() {
        if (!$this->_grupos) {
            $this->loadGrupos();
        }
        
    	return (count($this->_grupos) == 0);
    }
}