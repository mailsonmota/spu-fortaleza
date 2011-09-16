<?php
/**
 * Spu_Entity_Abstract
 * Abstração para as Entidades do SPU
 * @author bruno <brunofcavalcante@gmail.com>
 * @package SPU
 */
class Spu_Entity_Abstract
{
	/**
	 * Método mágico
	 * @param unknown_type $property
	 */
	public function __get($property) {
		$methodName = 'get' . ucwords($property);
		return $this->$methodName();
	}
}