<?php
class Spu_Entity_Abstract
{
	public function __get($property) {
		$methodName = 'get' . ucwords($property);
		return $this->$methodName();
	}
}