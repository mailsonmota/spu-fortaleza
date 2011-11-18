<?php
/**
 * Representa um Tipo de Documento do SPU
 * 
 * @package SPU
 * @see Spu_Entity_Classification_Abstract
 */
class Spu_Entity_Classification_TipoDocumento extends Spu_Entity_Classification_Abstract
{
    protected $_parent;
    protected $_parentRaiz;
    
    public function getParent()
    {
        return $this->_parent;
    }
    
    public function setParent($value)
    {
        $this->_parent = $value;
    }

    public function getParentRaiz()
    {
        return $this->_parentRaiz;
    }
    
    public function setParentRaiz($value)
    {
        $this->_parentRaiz = $value;
    }
}
