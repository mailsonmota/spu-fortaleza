<?php

/**
 * Modelo para as classes de serviço do SPU
 * 
 * @author Bruno Cavalcante <brunofcavalcante@gmail.com>
 * @package SPU
 * @see Alfresco_Rest_Abstract
 */
abstract class Spu_Service_Abstract extends Alfresco_Rest_Abstract
{

    public function __construct($ticket = null)
    {
        $this->setBaseUrl(self::getAlfrescoUrl());
        if (isset($ticket)) {
            $this->setTicket($ticket);
        }
    }

    /**
     * Retorna a URL para o alfresco, definida no application.ini
     * 
     * @return string
     */
    public static function getAlfrescoUrl()
    {
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', 'production');
        return $config->get('alfresco')->get('url') . '/service';
    }

    /**
     * Verifica se existe a chave e, se sim, retorna o valor de um campo em um Hash.
     * 
     * @param array $hash
     * @param string $hashField
     * @return mixed
     */
    protected function _getHashValue($hash, $hashField)
    {
        if (!isset($hash[$hashField])) {
            return null;
        }

        if (is_array($hash[$hashField])) {
            $value = array();
            foreach ($hash[$hashField] as $hashKey => $hashValue) {
                $value[$hashKey] = $hashValue;
            }
        } else {
            $value = $hash[$hashField];
        }

        return $value;
    }

    protected function _getEntryCmisXml(DOMDocument $xml)
    {
        $entrys = array();

        foreach ($xml->getElementsByTagName("entry") as $key => $entry) {
            $entrys[] = $this->_getObjectEntryCmisXml($entry);
        }

        return $entrys;
    }

    protected function _getObjectEntryCmisXml(DOMElement $entry)
    {
        $elements = $entry->getElementsByTagName('object')->item(0)
            ->getElementsByTagName('properties')->item(0)->getElementsByTagName('*');

        $dados = array();
        foreach ($elements as $element) {
            $id = $element->getAttribute('propertyDefinitionId');
            
            if ($id)
                $dados[$id] = $element->nodeValue;
        }
        return $dados;
    }

}