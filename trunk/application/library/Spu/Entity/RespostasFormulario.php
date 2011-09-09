<?php
class Spu_Entity_RespostasFormulario extends Spu_Entity_Abstract
{
    protected $_data;
    
    //FIXME: Pegar o Label da Pergunta na Resposta do Formulario
    public function loadFromXML($xmlString)
    {
        $xml = simplexml_load_string($xmlString);
        $data = $this->_XMLToArray($xml);
        $data = array_pop($data);
        
        $this->_data = $data;
    }
    
    protected function _XMLToArray($xml)
    { 
        if ($xml instanceof SimpleXMLElement) { 
            $children = $xml->children(); 
            $return = null; 
        } 
    
        foreach ($children as $element => $value) { 
            if ($value instanceof SimpleXMLElement) { 
                $values = (array)$value->children(); 
          
                if (count($values) > 0) { 
                    $return[$element] = $this->_XMLToArray($value); 
                } else { 
                    if (!isset($return[$element])) { 
                        $return[$element] = (string)$value; 
                    } else { 
                        if (!is_array($return[$element])) { 
                            $return[$element] = array($return[$element], (string)$value); 
                        } else { 
                            $return[$element][] = (string)$value; 
                        } 
                    } 
                } 
            } 
        } 
      
        if (is_array($return)) { 
            return $return;
        } else { 
            return $false; 
        } 
    } 
    
    public function hasData()
    {
        return (is_array($this->_data));
    }
    
    public function getData() {
        return $this->_data;
    }
    
    public function setData($value)
    {
        $this->_data = $value;
    }
    
    public function getAdjustedKeyName($key)
    {
        $key = preg_replace('/([^\s])([A-Z])/', '\1 \2', $key);
        $key = strtolower($key);
        $key = ucfirst($key);
        return $key;
    }
}