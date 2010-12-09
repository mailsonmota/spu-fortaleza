<?php

class JsonPostAdapter extends JsonGetAdapter
{
    public function encode($data) {
        $json = json_encode($data);
        return $json;
    }
    
    public function updateOptions($options) {
        $options[CURLOPT_HTTPHEADER] = array('Content-Type: application/json');
        return $options;
    }
}
