<?php
/**
 * Message: Helper para exibir as mensagens de sucesso/erro/info ao usuário
 * @author bruno
 * @package SGC
 */
class Zend_View_Helper_Message extends Zend_View_Helper_Abstract
{
    private $_texto = NULL;
    private $_tipo  = NULL;
    
    public function message()
    {
        return $this;
    }
    
    public function setTexto($texto)
    {
        $this->_texto = $texto;
    }
    
    public function setTextoLiteral($texto)
    {
        $this->_texto = $texto;
    }
    
    public function setTipo($valor)
    {
    	$this->_tipo = $valor;
    }
    
    public function render()
    {
        if ($this->_texto === NULL AND $this->_tipo === NULL) {
            return NULL;
        }
        
        return "<div class='message {$this->_tipo}'>{$this->_texto}</div>";
    }
}