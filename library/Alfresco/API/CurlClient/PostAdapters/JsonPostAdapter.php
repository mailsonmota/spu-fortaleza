<?php

class JsonPostAdapter extends JsonGetAdapter
{
    public function encode($data) {
        // TODO ver função json_encode()
//        $json = "{";
//        
//        $count = count($data);
//        
//        $i = 1;
//        foreach($data as $key => $value) {
//            $json .= "\"" . $key . "\" : \"" . $value . "\"";
//            if ($i < $count) {
//                $json .= ", ";
//            }
//            $i++;
//        }
//        
//        $json .= "}";
        $json = json_encode($data);
        return $json;
    }
    
    public function updateOptions($options) {
        $options[CURLOPT_HTTPHEADER] = array('Content-Type: application/json');
        return $options;
    }
}
