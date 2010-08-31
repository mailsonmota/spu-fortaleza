<?php
/**
 * Message: Helper para exibir as mensagens de sucesso/erro/info ao usuário
 * @author bruno
 * @package SGC
 */
class Zend_View_Helper_message extends Zend_View_Helper_Abstract
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
        switch ($valor) {
            case 'success':
                $this->_tipo = 'success';
                break;
                
            case 'error':
                $this->_tipo = 'error';
                break;
                
            case 'notice':
                $this->_tipo = 'notice';
                break;
                
            default:
                $this->_tipo = 'notice';
                break;
        }
    }
    
    public function render()
    {
        if ($this->_texto === NULL AND $this->_tipo === NULL) {
            return NULL;
        }
        
        $str  = '<div id="message" class="' . $this->_tipo . '">';
        $str .= $this->_texto;
        $str .= '</div>';
        
        return $str;
    }
}