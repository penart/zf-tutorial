<?php

/**
 * Интерфейс курса валют
 * с одним методом.
 */
interface Test_CurrencyCourseInterface 
{
    /**
     * Получение курса валют в виде ассоциативного массива
     * 
     * @return array()
     * @throws Exception Ошибка разбора курса валют
     */
    public function getCurrencyCourse();
}
