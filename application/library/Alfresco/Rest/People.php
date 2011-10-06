<?php
/**
 * @see Alfresco_Person
 */
require_once dirname(__FILE__) . '/../Person.php';

/**
 * Alfresco People methods
 * 
 * @author Bruno Cavalcante <brunofcavalcante@gmail.com>
 * @package Alfresco-PHP
 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License 3
 */
class Alfresco_Rest_People extends Alfresco_Rest_Abstract
{
    private $_peopleBaseUrl = 'people';
    private $_peoplePreferencesUrl = 'preferences';
    private $_peopleSitesUrl = 'sites';
    
    /**
     * List users
     * 
     * GET /alfresco/service/api/people?filter={filter}
     * 
     * @param string $filter
     * @return Alfresco_Person[]
     */
    public function listPeople($filter = null)
    {
        $url = $this->getBaseUrl() . "/api/" . $this->_peopleBaseUrl;
        
        if (isset($filter)) {
            $url .= "?filter=" . $filter;
        }
        
        $result = $this->_doAuthenticatedGetRequest($url);

        $people = array();
        foreach ($result['people'] as $personResult) {
            $people[] = $this->_getPersonFromJson($personResult);
        }

        return $people;
    }
    
    /**
     * Get Person from JSON
     * 
     * @param array $json
     * @return Alfresco_Person
     */
    protected function _getPersonFromJson($json)
    {
        $person = new Alfresco_Person();
        foreach ($json as $key => $value) {
            $person->$key = $value;
        }
        
        return $person;
    }
    
    /**
     * Get person details
     * 
     * GET /alfresco/service/api/people/{userName}
     * 
     * @return Alfresco_Person
     */
    public function getPerson($userName)
    {
        $url = $this->getBaseUrl() . "/api/" . $this->_peopleBaseUrl . "/" . $userName;
        $result = $this->_doAuthenticatedGetRequest($url);
        
        return $this->_getPersonFromJson($result);
    }
}