<?php
/**
 * Representa um formulário de assunto do SPU
 * 
 * @author Bruno Cavalcante <brunofcavalcante@gmail.com>
 * @package SPU
 * @see Spu_Entity_Abstract
 */
class Spu_Entity_Formulario extends Spu_Entity_Abstract
{
    protected $_data;
    
    /**
     * @return boolean
     */
    public function hasData()
    {
        return (isset($this->_data));
    }
    
    public function getData() {
        return $this->_data;
    }
    
    public function setData($value)
    {
        $this->_data = $value;
    }
}