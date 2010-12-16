<?php
require_once('BaseEntity.php');
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
    
    public function getGrupos($userName) {
    	$dao = $this->_getDao();
        return $dao->fetchGroups($userName);
    }

    public function getNomeCompleto()
    {
    	return $this->_nome . ' ' . $this->_sobrenome;
    }
    
	protected function _getDao() {
    	$dao = new UsuarioDao(self::ALFRESCO_URL, $this->_getTicket());
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
}