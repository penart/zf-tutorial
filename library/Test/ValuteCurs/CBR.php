<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Test_ValuteCurs_CBR
{
    const URL = 'http://www.cbr.ru/scripts/XML_daily.asp';
    
    public static function getValuteCurs(){
        //Загрузим данные из источника
        $client = new Zend_Http_Client(self::URL);
        $response = $client->request('GET');
        $data = simplexml_load_string($response->getBody());
        return $data;
    }

}

