<?php
/**
 * Representa um usuário do SPU
 * 
 * @author Bruno Cavalcante <brunofcavalcante@gmail.com>
 * @package SPU
 * @see Spu_Entity_Abstract
 */
class Spu_Entity_Usuario extends Spu_Entity_Abstract
{
    protected $_nome;
    protected $_sobrenome;
    protected $_email;
    protected $_login;
    protected $_grupos;
    
    public function getNome()
    {
        return $this->_nome;
    }
    
    public function setNome($nome)
    {
        $this->_nome = $nome;
    }
    
    public function getSobrenome()
    {
        return $this->_sobrenome;
    }
    
    public function setSobrenome($sobrenome)
    {
        $this->_sobrenome = $sobrenome;
    }
    
    public function getEmail()
    {
        return $this->_email;
    }
    
    public function setEmail($email)
    {
        $this->_email = $email;
    }
    
    public function getLogin()
    {
        return $this->_login;
    }
    
    public function setLogin($login)
    {
        $this->_login = $login;
    }
    
    /**
     * @return Spu_Entity_Grupo[]
     */
    public function getGrupos()
    {
        return $this->_grupos;
    }
    
    public function setGrupos($grupos)
    {
        $this->_grupos = $grupos;
    }
    
    public function getNomeCompleto()
    {
        return $this->_nome . ' ' . $this->_sobrenome;
    }
    
    public function getNomeOuSobrenome()
    {
        return ($this->_nome) ? $this->_nome : $this->_sobrenome;
    } 
    
    /**
     * @return boolean
     */
    public function isAdministrador()
    {
        if (!isset($this->_grupos)) {
            throw new Exception('Grupos não carregados');
        }
        
        foreach ($this->_grupos as $grupo) {
            if ($grupo->isAdministrador()) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * @return boolean
     */
    public function isGuest()
    {
        if (!isset($this->_grupos)) {
            throw new Exception('Grupos não carregados');
        }
        
        return (count($this->_grupos) == 0);
    }
}