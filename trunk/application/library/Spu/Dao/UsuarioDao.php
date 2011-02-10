<?php
require_once('BaseDao.php');
Loader::loadEntity('Usuario');
class UsuarioDao extends BaseDao
{
    public function fetchAll($filter = null)
    {
        $api = $this->_getApi();
        return $api->listPeople(filter);
    }
    
    public function find($username)
    {
        $api = $this->_getApi();
        return $this->loadFromHash($api->getPerson($username));
    }
    
    public function fetchGroups($username)
    {
        $api = $this->_getApi();
        return $this->_loadGruposFromHash($api->getGroups($username));
    }
    
    protected function _getApi()
    {
        $api = new Alfresco_Rest_People(self::ALFRESCO_URL, $this->getTicket());
        return $api;
    }
    
    public function loadFromHash($hash) {
        $usuario = new Usuario();
        
    	$usuario->setNome($this->_getHashValue($hash, 'firstName'));
        $usuario->setSobrenome($this->_getHashValue($hash, 'lastName'));
        $usuario->setEmail($this->_getHashValue($hash, 'email'));
        $usuario->setLogin($this->_getHashValue($hash, 'userName'));
        $usuario->setGrupos($this->fetchGroups($usuario->getLogin()));
        
        return $usuario;
    }
    
    protected function _loadGruposFromHash($hash) {
        $grupos = array();
        if (count($hash) > 0) {
            foreach ($hash as $hashGrupo) {
                $grupo = new Grupo();
                $grupo->setNome($hashGrupo['item']);
                $grupos[] = $grupo;
            }
        }
        
        return $grupos;
    }
}