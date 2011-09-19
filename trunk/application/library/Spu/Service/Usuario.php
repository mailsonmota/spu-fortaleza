<?php
class Spu_Service_Usuario extends Spu_Service_Abstract
{
    public function fetchAll($filter = null)
    {
        return $this->_getApi()->listPeople($filter);
    }
    
    public function find($username)
    {
        $api = $this->_getApi();
        return $this->loadFromHash($api->getPerson($username));
    }
    
    public function fetchGroups($username)
    {
        $url = $this->getBaseUrl() . "/getGroups";
        
        $result = $this->_doAuthenticatedGetRequest($url);
        $hash = (isset($result['groups'])) ? $result['groups'] : array();
        
        return $this->_loadGruposFromHash($hash);
    }
    
    protected function _getApi()
    {
        $api = new Alfresco_Rest_People(self::getBaseUrl(), $this->getTicket());
        return $api;
    }
    
    public function loadFromHash($hash)
    {
        $usuario = new Spu_Entity_Usuario();
        
        $usuario->setNome($this->_getHashValue($hash, 'firstName'));
        $usuario->setSobrenome($this->_getHashValue($hash, 'lastName'));
        $usuario->setEmail($this->_getHashValue($hash, 'email'));
        $usuario->setLogin($this->_getHashValue($hash, 'userName'));
        $usuario->setGrupos($this->fetchGroups($usuario->getLogin()));
        
        return $usuario;
    }
    
    protected function _loadGruposFromHash($hash)
    {
        $grupos = array();
        if (count($hash) > 0) {
            foreach ($hash as $hashGrupo) {
                $grupo = new Spu_Entity_Grupo();
                $grupo->setNome($hashGrupo['item']);
                $grupos[] = $grupo;
            }
        }
        
        return $grupos;
    }
}