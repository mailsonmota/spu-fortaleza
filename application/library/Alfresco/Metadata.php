<?php
/**
 * @see Alfresco_Abstract
 */
require_once 'Abstract.php';

/**
 * Represents a Metadata from Alfresco
 * 
 * @author Bruno Cavalcante <brunofcavalcante@gmail.com>
 * @package Alfresco-PHP
 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License 3
 */
class Alfresco_Metadata extends Alfresco_Abstract
{
    /**
     * @var string
     */
    protected $_name;
    
    /**
     * @var string
     */
    protected $_value;
    
    /**
     * @var string
     */
    protected $_displayName;
    
    /**
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }
    
    /**
     * @param string $value
     */
    public function setName($value)
    {
        $this->_name = $value;
    }
    
    /**
     * @return string
     */
    public function getValue()
    {
        return $this->_value;
    }
    
    /**
     * @param string $value
     */
    public function setValue($value)
    {
        $this->_value = $value;
    }
    
    /**
     * @return string
     */
    public function getDisplayName()
    {
        return $this->_displayName;
    }
    
    /**
     * @param string $value
     */
    public function setDisplayName($value)
    {
        $this->_displayName = $value;
    }
}
