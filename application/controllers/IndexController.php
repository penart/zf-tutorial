<?php

class IndexController extends Zend_Controller_Action
{
    private $_cache;    //Локальный кэш
    
    public function init()
    {
        //Инициализируем кэш        
        $frontendOptions = array(
            'lifetime' => 86400, // cache lifetime 24 часа
            'automatic_serialization' => true
        );
 
        $backendOptions = array(
            'cache_dir' => '../tmp/' // Директория для файлового кеша
        );

        $this->_cache = Zend_Cache::factory(
                'Core',
                'File',
                $frontendOptions,
                $backendOptions
                );
        
    }
    
    /**
     * Отображение начальной страницы
     */
    public function indexAction()
    {      
        $this->view->headTitle('Курс валют');
        $this->view->pageTitle = 'Курс валют';
    }
    
    /**
     * Загрузка курса валют из кэша по ajax запросу
     */
    public function loadAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        
        //проверим если кэш устарел обновим данные
        if (!$rows = $this->_cache->load('rows')) {
            $curCourse = new Application_Model_DbTable_CurrencyCourse();
            $data = $curCourse->fetchAll(['is_active=?' => 1]);
            $rows = array();
            foreach($data as $item){
                $row = array(
                    'id' => $item->id,
                    'dt' => $item->dt,
                    'isActive' => $item->is_active,
                    'numericCode' => $item->numeric_code,
                    'charCode' => $item->char_code,
                    'nominal' => $item->nominal,
                    'name' => $item->name,
                    'value' => $item->value
                );
                array_push($rows, $row);
            }
            $this->_cache->save($rows, 'rows');            
        } 

        $this->_helper->json(array('rows'=>$rows, 'rowcount'=>count($rows)));                
    }
    
    /**
     * Загрузка обновлёния курса валют из источника по ajax запросу
     */
    public function refreshAction()
    {
        //перечитаем данные
        $curCourse = new Application_Model_DbTable_CurrencyCourse();

        try{
            $curCourse->loadCurrencyCourseFromSource();
            //очистим кеш
            $this->_cache->remove('rows');
            //отдадим клиенту
            $this->forward('load');
        }
        catch(Exception $ex){
            //Отправим ошибку клиенту
            $this->_helper->json(array(
                'error'=> true,
                'error_message' => $ex->getMessage(),
                ));
        }
    }
    
    /**
     * Загрузка списка валют для формы выбора по ajax запросу
     */
    public function selectAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        
        $curCourse = new Application_Model_DbTable_CurrencyCourse();
        $data = $curCourse->fetchAll();
        $rows = array();
        foreach($data as $item){
            $row = array(
                'id' => $item->id,
                'isActive' => $item->is_active,
                'numericCode' => $item->numeric_code,
                'charCode' => $item->char_code,
                'nominal' => $item->nominal,
                'name' => $item->name,
                'value' => $item->value
            );
            array_push($rows, $row);
        }

        $this->_helper->json(array('rows'=>$rows, 'rowcount'=>count($rows)));                
    }
    
    /**
     * Сохранение списка выбранных валют
     */
    public function saveAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        
        $curCourse = new Application_Model_DbTable_CurrencyCourse();

        $keyPars = $this->getParam('keyPars');
        $keyParsArray = explode(',', $keyPars);
        
        foreach($keyParsArray as $keyPar){ 
            $keyParArray = explode('=', $keyPar, 2);                        
            if (count($keyParArray) == 2 && ($key = intval($keyParArray[0])) != 0) {                   
                    $val = intval($keyParArray[1]);                    
                    if (($rows = $curCourse->find($key))) {
                        $rows->current()->is_active = $val;
                        $rows->current()->save();
                    }
            }                            
        }
            
        $this->_cache->remove('rows');
        $this->loadAction();                
    }
    
}

