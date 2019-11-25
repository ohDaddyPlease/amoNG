<?php

require_once('AmoAccount.php');
require_once('AmoAuthorization.php');


class AmoRequest{

    public function __construct(AmoAccount $account){

        $this->account = $account;

    }

    public function request(array $data = array('url' => '', 'method' => '', 'data' => [], 'params' => [])){

        if(!empty($data['params'])){

            $params = [];
            while(current($data['params'])){
                $params[] = key($data['params']) . '=' . implode(',', $data['params']);
                next($data['params']);
            }

            $params = implode('&', $params);

        }

<<<<<<< HEAD
        $auth = new AmoAuthorization($this->account);
=======
        $auth = new AmoAuthorization;
>>>>>>> 4af2d459a77d554bf0e7e813b0e30688a4ac81ab
        $tokens = $auth->getTokens();
        $access_token = $tokens['access_token'];
        if($data['url'] == '/oauth2/access_token'){
            $headers = ['Content-Type:application/json'];
        }else $headers = ['Authorization: Bearer ' . $access_token];

        $curl = curl_init();
        curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl,CURLOPT_USERAGENT, 'amoCRM-oAuth-client/1.0');
        curl_setopt($curl,CURLOPT_URL, $this->account->APIurl . $data['url'] . (empty($data['params']) ? '' : ('?' . $params)));
        curl_setopt($curl,CURLOPT_HTTPHEADER,$headers);
        curl_setopt($curl,CURLOPT_HEADER, false);
        if(strtolower($data['method']) == 'post'){
            curl_setopt($curl,CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($curl,CURLOPT_POSTFIELDS, json_encode($data['data']));
        }
        curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($curl,CURLOPT_SSL_VERIFYHOST, 2);
        $out = curl_exec($curl);
        $curlCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
    
        $curlCode = (int)$curlCode;
        $errors = [
            400 => 'Bad request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not found',
            500 => 'Internal server error',
            502 => 'Bad gateway',
            503 => 'Service unavailable',
        ];
    
        try
        {
            if ($curlCode < 200 && $curlCode > 204) {
                throw new Exception(isset($errors[$curlCode]) ? $errors[$curlCode] : 'Undefined error', $curlCode);
            }
        }
        catch(\Exception $e)
        {
            die('Ошибка: ' . $e->getMessage() . PHP_EOL . 'Код ошибки: ' . $e->getCode());
        }
        $response = json_decode($out, true);
        return $response;
    }



    public function getAccountInfo(array $params = []){
        return $this->request(array(
            'url' => '/api/v2/account',
            'params' => $params
        ));
    }



    public function addLeads(array $data){
        return $this->request(array(
            'url' => '/api/v2/leads',
            'method' => 'POST',
            'data' => $data
        ));
    }

    public function getLeads(array $params = []){
        return $this->request(array(
            'url' => '/api/v2/leads',
            'params' => $params
        ));
    }

    public function leadExists(){ //NOTE: дописать

    }

    public function leadHasContacts(){ //NOTE: дописать

    }



    public function addContacts(array $data){
        return $this->request(array(
            'url' => '/api/v2/contacts',
            'method' => 'POST',
            'data' => $data
        ));
    }

    public function getContacts(array $params = []){
        return $this->request(array(
            'url' => '/api/v2/contacts',
            'params' => $params
        ));
    }

    public function contactExists(){ //NOTE: дописать

    }

    public function contactHasLeads(){ //NOTE: дописать

    }



    public function addCompanies(array $data){
        return $this->request(array(
            'url' => '/api/v2/companies',
            'method' => 'POST',
            'data' => $data
        ));
    }

    public function getCompanies(array $params = []){
        return $this->request(array(
            'url' => '/api/v2/companies',
            'params' => $params
        ));
    }



    public function addCustomers(array $data){
        return $this->request(array(
            'url' => '/api/v2/customers',
            'method' => 'POST',
            'data' => $data
        ));
    }

    public function getCustomers(array $params = []){
        return $this->request(array(
            'url' => '/api/v2/customers',
            'params' => $params
        ));
    }



    public function addTasks(array $data){
        return $this->request(array(
            'url' => '/api/v2/tasks',
            'method' => 'POST',
            'data' => $data
        ));
    }

    public function getTasks(array $params = []){
        return $this->request(array(
            'url' => '/api/v2/tasks',
            'params' => $params
        ));
    }



    public function addNotes(array $data){
        return $this->request(array(
            'url' => '/api/v2/notes',
            'method' => 'POST',
            'data' => $data
        ));
    }

    public function getNotes(array $params = []){
        return $this->request(array(
            'url' => '/api/v2/notes',
            'params' => $params
        ));
    }

}
