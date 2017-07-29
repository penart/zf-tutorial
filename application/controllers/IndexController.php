<?php

class IndexController extends Zend_Controller_Action
{
    private $_cache;    //Локальный кэш
    
    public function init()
    {
        /* Initialize action controller here */
        
        $frontendOptions = array(
            'lifetime' => 86400, // cache lifetime 24 часа
            'automatic_serialization' => true
        );
 
        $backendOptions = array(
            'cache_dir' => '../tmp/' // Директория для файлового кеша
        );

        $this->_cache = Zend_Cache::factory(
                'Core'
                , 'File'
                , $frontendOptions
                , $backendOptions
                );

    }

    private function _loadToDb()
    {
        $valCurs = new Application_Model_DbTable_ValCurs();
        $srcValCurs = new Test_ValuteCursCBR();
        $data = $srcValCurs->getValuteCurs();
        //$dt = $data['dt'];
        $dt = (new Zend_Date())->toString('yyyy-MM-dd HH:mm:ss');
        
        foreach($data['rows'] as $item){
            $row = $valCurs->fetchRow("num_code='".$item->getNumCode()."'");
            if ($row==null) {
                $row = $valCurs->createRow();                
                $row->dt = $dt;
                $row->num_code = $item->getNumCode();
                $row->char_code = $item->getCharCode();
                $row->nominal = $item->getNominal();
                $row->name = $item->getName();
                $row->value = $item->getValue();
                $row->save();                
            } else {                
                $row->dt = $dt;
                $row->num_code = $item->getNumCode();
                $row->char_code = $item->getCharCode();
                $row->nominal = $item->getNominal();
                $row->name= $item->getName();
                $row->value = $item->getValue();                
                $row->save();
            }
        }
        
    }
   
    
    public function indexAction()
    {      
        $this->view->headTitle('Курс валют');
        $this->view->pageTitle = 'Курс валют';
    }

    public function loadAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        
        //проверим если кэш устарел обновим данные
        if (!$rows = $this->_cache->load('rows')) {
            $valCurs = new Application_Model_DbTable_ValCurs();
            $rows = $valCurs->fetchAll('is_active=1')->toArray();
            $this->_cache->save($rows, 'rows');            
        } 

        $this->_helper->json(array('rows'=>$rows,'rowcount'=>count($rows)));                
    }

    public function refreshAction()
    {
        //перечитаем данные
        $this->_loadToDb();
        //очистим кеш
        $this->_cache->remove('rows');
        //отдадим клиенту
        $this->loadAction();
    }

    public function selectAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        
        $valCurs = new Application_Model_DbTable_ValCurs();
        $rows = $valCurs->fetchAll()->toArray();

        $this->_helper->json(array('rows'=>$rows,'rowcount'=>count($rows)));                
    }
    
    public function saveAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        $keyPars = $this->getParam('keyPars');
        $valCurs = new Application_Model_DbTable_ValCurs();
        $arrKeyPars = explode(',',$keyPars);
        
        foreach($arrKeyPars as $keyPar){
            $arrKeyPar = explode('=',$keyPar);
            if ($rows = $valCurs->find($arrKeyPar[0])) {
                $rows->current()->is_active = $arrKeyPar[1];
                $rows->current()->save();
            }
        }
            
        $this->_cache->remove('rows');                    
        $this->loadAction();                
    }
    
}

