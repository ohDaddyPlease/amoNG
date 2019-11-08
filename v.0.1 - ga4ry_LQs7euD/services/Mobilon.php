<?php

class Mobilon{

    public function __construct(){
        
    }

    public function getWebhook(){
        $data = json_decode(file_get_contents('php://input'), true);
        if(($data['state'] !== 'IVR')&&(!isset($data['inputDigits']))) exit();
        return $data;
    }
}