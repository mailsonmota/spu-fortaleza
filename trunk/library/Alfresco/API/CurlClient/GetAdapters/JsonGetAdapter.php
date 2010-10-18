<?php

class JsonGetAdapter
{
    public function decode($data) {
        // TODO fazer ou procurar função que valide json
        return json_decode($data);
    }
}
