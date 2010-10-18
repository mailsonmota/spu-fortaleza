<?php

require_once('GetAdapters/JsonGetAdapter.php');
require_once('PostAdapters/JsonPostAdapter.php');

class CurlClient
{
    const DEFAULT_INPUT_FORMAT = 'json';
    const DEFAULT_RETURN_FORMAT = 'json';
    
    private function _doRequest($url, $options = array()) {
        $ch = curl_init();
        
        $options[CURLOPT_URL] = $url;
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        curl_setopt_array($ch, $options);
        
        $result = curl_exec($ch);

        curl_close($ch);

        return $result;
    }

    public function doGetRequest($url, $returnFormat = self::DEFAULT_RETURN_FORMAT) {
        $options[CURLOPT_CUSTOMREQUEST] = 'GET';
        $result = $this->_doRequest($url, $options);
        return $result;
    }

    public function doPostRequest(
        $url, $postData, $postDataFormat = self::DEFAULT_INPUT_FORMAT, $returnFormat = self::DEFAULT_RETURN_FORMAT
        ) {
        $options[CURLOPT_CUSTOMREQUEST] = 'POST';
        
        $postAdapterObj = $this->getPostAdapter($postDataFormat);
        
        $jsonData = $postAdapterObj->encode($postData);
        $options = $postAdapterObj->updateOptions($options);
        // TODO adicionar $jsonData no $options não manualmente (como está feito na próxima linha)
        $options[CURLOPT_POSTFIELDS] = $jsonData; // FIXME
        
        $result = $this->_doRequest($url, $options);
        
        $getAdapterObj = $this->getGetAdapter($returnFormat);
        
        $return = $getAdapterObj->decode($result);
        
        return $return;
    }

    public function doDeleteRequest($url, $options = array()) {
        $options[CURLOPT_CUSTOMREQUEST] = 'DELETE';
        $result = $this->_doRequest($url, $options);
        return $result;
    }

    public function doPutRequest($url, $options = array()) {
        $options[CURLOPT_CUSTOMREQUEST] = 'PUT';
        $result = $this->_doRequest($url, $options);
        return $result;
    }

    public function getGetAdapter($adapterFormat = self::DEFAULT_INPUT_FORMAT) {
        $adapterFormat = ucfirst(strtolower($adapterFormat));
        $classname = $adapterFormat . "GetAdapter";
        
        require_once('GetAdapters/' . $classname . '.php');
        
        $adapterObject = new $classname();
        
        return $adapterObject;
    }
    
    public function getPostAdapter($adapterFormat = self::DEFAULT_RETURN_FORMAT) {
        $adapterFormat = ucfirst(strtolower($adapterFormat));
        $classname = $adapterFormat . "PostAdapter";
        
        require_once('PostAdapters/' . $classname . '.php');
        
        $adapterObject = new $classname();
        
        return $adapterObject;
    }
}