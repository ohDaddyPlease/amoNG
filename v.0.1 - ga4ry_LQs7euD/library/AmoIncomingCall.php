<?php

class AmoIncomingCall{

    public function __construct($caller){

        if(trim(empty($caller)) || strlen(trim((string) $caller)) == 0) throw new \Exception('Phone number must be set');

        $this->callerTelephone = trim((string) $caller);
    }

    public function handlePhone($caller = '') : string {
        $callerNumber = trim((string) $caller) ? str_split(trim((string) $caller)) : str_split($this->callerTelephone);
        if((string) $callerNumber[0] == '+'){
            array_shift($callerNumber);
            array_shift($callerNumber);
            $callerNumber = implode($callerNumber);
        }elseif(((string) $callerNumber[0] == '7' || (string) $callerNumber[0] == '8') && count($callerNumber) == 11){
            array_shift($callerNumber);
            $callerNumber = implode($callerNumber);
        }
        if(is_array($callerNumber)) $callerNumber = implode($callerNumber);

        return (string) $callerNumber;
    }

}