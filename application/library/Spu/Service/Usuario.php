<?php
/**
 * Classe para acessar os serviços de usuário do SPU
 * 
 * @author Bruno Cavalcante <brunofcavalcante@gmail.com>
 * @package SPU
 * @see Spu_Service_Abstract
 */
class Spu_Service_Usuario extends Spu_Service_Abstract
{
    /**
	 * Retorna o usuário através do seu username
	 * 
	 * @param string $username
	 * @return Spu_Entity_Usuario
	 */
    public function find($username)
    {
        return $this->loadFromObject($this->_getApi()->getPerson($username));
    }
    
    /**
     * Retorna os grupos (de permissão) do usuário através do username
     * 
     * @param string $username
     * @return Spu_Entity_Grupo[]
     */
    public function fetchGroups($username = null)
    {
        $url = $this->getBaseUrl() . "/usuario/grupos/get";
        $result = $this->_doAuthenticatedGetRequest($url);
        
        $hash = (isset($result['groups'])) ? $result['groups'] : array();
        
        return $this->_loadGruposFromHash($hash);
    }
    
    public function getGropus()
    {
        $groups = $this->fetchGroups();
        $val= "";
        
        foreach ($groups as $key => $group) {
            $val .= $group->nome . ($key < count($groups)-1 ? " > " :  "");
        }
        
        return array_unique(explode(" > ", $val));
    }
    
    /**
     * Retorna a API em PHP de People (usuarios) do Alfresco
     * 
     * @return Alfresco_Rest_People
     */
    protected function _getApi()
    {
        return new Alfresco_Rest_People(self::getBaseUrl(), $this->getTicket());
    }
    
    /**
     * Carrega o usuário através de um hash
     * 
     * @param array $hash
     * @return Spu_Entity_Usuario
     */
    public function loadFromObject($object)
    {
        $usuario = new Spu_Entity_Usuario();
        
        $usuario->setNome($object->firstName);
        $usuario->setSobrenome($object->lastName);
        $usuario->setEmail($object->email);
        $usuario->setLogin($object->userName);
        $usuario->setGrupos($this->fetchGroups($usuario->getLogin()));
        
        return $usuario;
    }
    
    /**
     * Carrega vários grupos através de um hash
     * 
     * @param array $hash
     * @return Spu_Entity_Grupo[]
     */
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