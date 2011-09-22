<?php
/**
 * Abstração para as Entidades do SPU
 * 
 * @author Bruno Cavalcante <brunofcavalcante@gmail.com>
 * @package SPU
 */
abstract class Spu_Entity_Abstract
{
	/**
	 * Método mágico
	 * @param string $property
	 */
	public function __get($property) {
		$methodName = 'get' . ucwords($property);
		return $this->$methodName();
	}
}