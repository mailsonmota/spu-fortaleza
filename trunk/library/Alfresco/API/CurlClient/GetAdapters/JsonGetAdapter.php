<?php

class JsonGetAdapter
{
    public function decode($data, $assoc = false) {
        // TODO fazer ou procurar função que valide json
        return json_decode($data, $assoc);
    }
}
