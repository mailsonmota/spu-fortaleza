<?php
class BaseAspect
{
	public function __get($property) {
        $methodName = 'get' . ucwords($property);
        return $this->$methodName();
    }
	
	protected function _getHashValue($hash, $hashField)
    {
        if (!isset($hash[$hashField])) {
            return null;
        }
        
        if (is_array($hash[$hashField])) {
            $value = array();
            foreach ($hash[$hashField] as $hashValue) {
                $value[] = $hashValue;
            }
        } else {
            $value = $hash[$hashField];
        }
        return $value;
    }
}