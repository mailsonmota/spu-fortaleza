<?php
class Zend_View_Helper_JsonAutocomplete extends Zend_View_Helper_Abstract
{
	public function jsonAutocomplete($results = array(), $id, $label, $value = null)
	{
		if (!$value) {
			$value = $label;
		}
		
		$json = '';
		if ($results) {
			$json .= '[';
			$i = 0;
			foreach ($results as $result) {
				$json .= (++$i > 1) ? ',' : '';
				$json .= '{';
				$json .= '"id":"' . $result->$id . '", ';
				$json .= '"label":"' . $result->$label . '", ';
				$json .= '"value":"' . $result->$value . '"';
				$json .= '}';
			}
			$json .= ']';
		}
		
		return $json;
	}
}