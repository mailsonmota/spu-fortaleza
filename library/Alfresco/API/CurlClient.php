<?php

class CurlClient
{
	private function doRequest($url, $options = array()) {
		$ch = curl_init();
		
        $options[CURLOPT_URL] = $url;
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt_array($ch, $options);
        
        $result = curl_exec($ch);
        curl_close($ch);
		
        return $result;
	}
	
	public function doGetRequest($url, $options = array()) {
		$options[CURLOPT_CUSTOMREQUEST] = 'GET';
		$result = $this->doRequest($url, $options);
		return $result;
	}
	
    public function doPostRequest($url, $options = array()) {
        $options[CURLOPT_CUSTOMREQUEST] = 'POST';
        $result = $this->doRequest($url, $options);
        return $result;
    }
	
    public function doDeleteRequest($url, $options = array()) {
        $options[CURLOPT_CUSTOMREQUEST] = 'DELETE';
        $result = $this->doRequest($url, $options);
        return $result;
    }
    
    public function doPutRequest($url, $options = array()) {
        $options[CURLOPT_CUSTOMREQUEST] = 'PUT';
        $result = $this->doRequest($url, $options);
        return $result;
    }
}