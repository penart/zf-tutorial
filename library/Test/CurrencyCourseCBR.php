<?php

require_once 'Test/CurrencyCourseInterface.php';
require_once 'Test/CurrencyCourse.php';

/**
 * Тестовый класс для получения курса валют
 * класс определяет метод для загрузки данных виз источника
 * в виде массива данных
 */
class Test_CurrencyCourseCBR implements Test_CurrencyCourseInterface
{
    //URL XML документа курса валют
    const URL = 'http://www.cbr.ru/scripts/XML_daily.asp';
    
    /**
     * Получение курса валют в виде ассоциативного массива
     * 
     * @return array()
     * @throws Exception Ошибка разбора курса валют
     */
    public function getCurrencyCourse()
    {
        try {
            $client = new Zend_Http_Client(self::URL);
            $response = $client->request('GET');
        } catch (Exception $ex) {
            throw new Exception('Ошибка получения курса валют:' .$ex->getMessage());
        }
        
        $data = simplexml_load_string($response->getBody());
        if ($data!==false) {
            $dt = (new Zend_Date($data->ValCurs['Date'], 
                            'dd.MM.yyyy'))->toString('yyyy-MM-dd HH:mm:ss');
            $result = array(); 
            foreach($data->Valute as $node){
                $row = new Test_CurrencyCourse();
                $row->setNumericCode($node->NumCode);
                $row->setCharCode($node->CharCode);
                $row->setNominal(intval($node->Nominal));
                $row->setName($node->Name);
                $row->setValue(floatval(str_replace(',', '.', $node->Value)));                                        
                array_push($result,$row);
            }                    
            return array('dt' => $dt, 'rows' => $result);
        } else {
            throw new Exception('Ошибка разбора xml документа курса валют.');            
        }
    }

}

