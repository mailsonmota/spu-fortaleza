<?php

/**
 * @see Alfresco_Rest_Abstract
 */
require_once 'Abstract.php';

/**
 * Update password service
 *
 * @author Igor Rocha <igor.roben@gmail.com>
 * @package alfresco-php-api
 */
class Alfresco_Rest_Person extends Alfresco_Rest_Abstract
{

    /**
     * URL package name for the services (this will be added to the alfresco base services url)
     *
     * @var string
     */
    private $_personBaseUrl = 'person/changepassword';

    /**
     * Basic authentication service
     *
     * POST /alfresco/service/api/login
     *
     * @param string $username
     * @param string $password
     * @return array array with the authentication ticket. Example: array('ticket' => 'ahq812GasLPlsmNMskneulasJsjak')
     */
    public function updatePassword($username, $oldpw, $newpw)
    {
        $url = "{$this->getBaseUrl()}/api/{$this->_personBaseUrl}/" . $username;
        $result = $this->_doAuthenticatedPostRequest($url, array('oldpw' => $oldpw, 'newpw' => $newpw));

        return $result;
    }

}
