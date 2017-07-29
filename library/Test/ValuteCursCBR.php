<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'Test/ValuteCursInterface.php';
require_once 'Test/ValuteCurs.php';

class Test_ValuteCursCBR implements Test_ValuteCursInterface
{
    const URL = 'http://www.cbr.ru/scripts/XML_daily.asp';
    
    public function getValuteCurs()
    {
        //Загрузим данные из источника
        $client = new Zend_Http_Client(self::URL);
        $response = $client->request('GET');
        $data = simplexml_load_string($response->getBody());
        $dt = (new Zend_Date($data->ValCurs['Date'], 'dd.MM.yyyy'))->toString('yyyy-MM-dd HH:mm:ss');
        $result = array();
        foreach($data->Valute as $node){
            $row = new Test_ValuteCurs();
            $row->setNumCode($node->NumCode);
            $row->setCharCode($node->CharCode);
            $row->setNominal(intval($node->Nominal));
            $row->setName($node->Name);
            $row->setValue(floatval(str_replace(',', '.', $node->Value)));                                        
            array_push($result,$row);
        }
        return array('dt'=>$dt,'rows'=>$result);
    }

}

