<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */

    }

    public function indexAction()
    {
        // action body
//        $this->view->MyTitle = "Hello wold!";
//        $this->view->headTitle("Index");
        //$this->loadAction();
        //$this->_helper->json(['a'=>'a','b'=>'b']);
        
        $this->loadAction();
        
    }

    public function loadAction()
    {
        $valCurs = new Application_Model_DbTable_ValCurs();
        $client = new Zend_Http_Client('http://www.cbr.ru/scripts/XML_daily.asp');
        $response = $client->request('GET');
        $data = simplexml_load_string($response->getBody());
        foreach($data->Valute as $node){
            $row = $valCurs->fetchRow("id='".$node['ID']."'");
            if($row==null){
                echo $node['ID'] . '<br/>';
                echo $node->NumCode . '<br/>';
                echo $node->CharCode . '<br/>';
                echo intval($node->Nominal) . '<br/>';
                echo $node->Name . '<br/>';
                echo $node->Value . '<br/>';
                echo floatval(str_replace(',', '.', $node->Value)) . '<br/>';

                $row = $valCurs->createRow();
                
                $row->id = $node['ID'];
                $row->num_code = $node->NumCode;
                $row->char_code = $node->CharCode;
                $row->nominal = intval($node->Nominal);
                $row->name= $node->Name;
                $row->value = floatval(str_replace(',', '.', $node->Value));
                $row->save();
            }
            else{
                
                $row->num_code = $node->NumCode;
                $row->char_code = $node->CharCode;
                $row->nominal = intval($node->Nominal);
                $row->name= $node->Name;
                $row->value = floatval(str_replace(',', '.', $node->Value));
                
                $row->save();
            }
        }
        //$this->_helper->json(['a'=>'a','b'=>'b']);
        
    }

}

