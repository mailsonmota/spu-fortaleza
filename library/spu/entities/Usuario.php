<?php

require_once('BaseAlfrescoEntity.php');
Loader::loadAlfrescoApiClass('AlfrescoPeople');

class Usuario extends BaseAlfrescoEntity
{
    protected $_nome;
    protected $_sobrenome;
    protected $_email;
    protected $_login;
    protected $_grupos;
    
    protected function _getObjetoServico() {
        return new AlfrescoPeople(self::ALFRESCO_URL, $this->_getTicket());
    }
    
    public function carregarPeloLogin($userName) {
        $hashDetalhesLogin = $this->_getObjetoServico()->getPerson($userName);
        $this->loadUsuarioFromHash($hashDetalhesLogin);
    }
    
    public function loadUsuarioFromHash($hashDetalhesLogin) {
        $this->setNome($hashDetalhesLogin['firstName']);
        $this->setSobrenome($hashDetalhesLogin['lastName']);
        $this->setEmail($hashDetalhesLogin['email']);
        $this->setLogin($hashDetalhesLogin['userName']);
    }
    
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
        return $this->_getObjetoServico()->getGroups($userName);
    }

}