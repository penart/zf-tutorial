<?php

class Application_Model_DbTable_CurrencyCourse extends Zend_Db_Table_Abstract
{

    protected $_name = 'currency_course';
    protected $_primary = 'id';

    /**
     * Загрузка курса валют из источника
     * @throws exceptionclass Ошибка разбора курса валют
     */
    public function loadCurrencyCourseFromSource()
    {
        $srcCurCourse = new Test_CurrencyCourseCBR();
        $data = $srcCurCourse->getCurrencyCourse();
        $dt = (new Zend_Date())->toString('yyyy-MM-dd HH:mm:ss');
        
        foreach($data['rows'] as $item){
            $row = $this->fetchRow(['numeric_code=?' => $item->getNumericCode()]);
            if ($row == null) {
                $row = $this->createRow();                
                $row->dt = $dt;
                $row->numeric_code = $item->getNumericCode();
                $row->char_code = $item->getCharCode();
                $row->nominal = $item->getNominal();
                $row->name = $item->getName();
                $row->value = $item->getValue();
                $row->save();                
            } else {                
                $row->dt = $dt;
                $row->numeric_code = $item->getNumericCode();
                $row->char_code = $item->getCharCode();
                $row->nominal = $item->getNominal();
                $row->name= $item->getName();
                $row->value = $item->getValue();                
                $row->save();
            }
        }
        
    }

}

