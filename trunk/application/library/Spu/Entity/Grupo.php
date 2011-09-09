<?php
class Spu_Entity_Grupo extends Spu_Entity_Abstract
{
    protected $_nome;
    
    public function getNome() {
        return $this->_nome;
    }
    
    public function setNome($value)
    {
        $this->_nome = $value;
    }
    
    public function isAdministrador() {
        foreach ($this->_getGruposAdministradores() as $grupo) {
            if (strpos($this->_nome, $grupo) > -1) {
                return true;
            }
        }
        
        return false;
    }
    
    protected function _getGruposAdministradores()
    {
        $grupos = array();
        $grupos[] = 'GROUP_ALFRESCO_ADMINISTRATORS';
        
        return $grupos;
    }
}