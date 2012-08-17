<?php

/**
 * @see Alfresco_Rest_Abstract
 */
require_once 'Abstract.php';

/**
 * Login/Logout service
 *
 * @author Gil Magno <gilmagno@gmail.com>
 * @package alfresco-php-api
 */
class Alfresco_Rest_Blog extends Alfresco_Rest_Abstract
{
    
    const BASE_URL_SITE = '/service/api/blog';
    
    public function getBlogPostsForDays($sitename, $days = 60)
    {
        
        $url = $this->getBaseUrl() . self::BASE_URL_SITE . "/site/$sitename/blog/posts/new?numdays=$days";
        
        $result = $this->_doAuthenticatedGetRequest($url);
        
        return $result;
    }
    
}
