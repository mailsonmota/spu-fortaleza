<?php
class BaseAlfrescoClass
{
    public function __get($property) {
        $methodName = 'get' . ucwords($property);
        return $this->$methodName();
    }
}