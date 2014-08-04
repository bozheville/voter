<?php
/**
 * Created by PhpStorm.
 * User: bozh
 * Date: 7/29/14
 * Time: 5:49 PM
 */

class ParentModel extends \Phalcon\Mvc\Collection {

    const INIT_YEAR = 2014;

    /**
     * Генератор случайной строки для пароля или соли.<br/>
     * Сначала выбирается алфавит, из которого будет составлена строка.<br />
     * Затем заданное количество раз добавляется к случайной строке случайные символы из алфавита<br />
     * Полученная строка перемешивается и обрезается до нужной длины<br />
     * Дальше идет валидация. Если валидация не была пройдена, функция вызывается рекурсивно до получения валидной строки.
     * @param int $base_len Длина случайной строки
     * @param int $rounds Количество раундов (для добавления дублирующихся символов)
     * @param string $alphatype Тип выборки алфавита. "luns" = lower+upper+numeric+specials; lu = lower+upper;
     * @return string Возвращает случайную строку, которая обязательно содержит хотя бы один символ каждого установленного в $alphatype типа
     */
    public static function getRandomString($base_len = 20, $rounds = 3, $alphatype = "luns") {
        $true_rounds = $rounds;
        $alphabet = array();
        if (strstr($alphatype, "l")) {
            $alphabet["lower"] = "qwertyuiopasdfghjklzxcvbnm";
        }
        if (strstr($alphatype, "u")) {
            $alphabet["upper"] = "QWERTYUIOPASDFGHJKLZXCVBNM";
        }
        if (strstr($alphatype, "n")) {
            $alphabet["numeric"] = "1234567890";
        }
        if (strstr($alphatype, "s")) {
            $alphabet["specials"] = ",.?;:[]{}!@#$%^&*()_+-=";
        }
        $crypt = "";
        while (--$rounds > 0) {
            $substr = str_split(implode("", $alphabet));
            shuffle($substr);
            $substr = implode("", $substr);
            $substr = substr($substr, rand(0, strlen($substr) - $base_len), $base_len);
            $crypt .= $substr;
        }
        $crypt = str_split($crypt);
        shuffle($crypt);
        $crypt = implode("", $crypt);
        $crypt = substr($crypt, rand(0, strlen($crypt) - $base_len), $base_len);
        if ($base_len >= count(array_keys($alphabet))) {
            $candidate = str_split($crypt);
            foreach ($alphabet as $key => $value) {
                $has[$key] = false;
            }
            foreach ($candidate as $value) {
                foreach ($has as $key => $val) {
                    if (strstr($alphabet[$key], $value)) {
                        $has[$key] = true;
                        break;
                    }
                }
            }
            foreach ($has as $val) {
                if (empty($val)) {
                    $crypt = self::getRandomString($base_len, $true_rounds, $alphatype);
                    break;
                }
            }
        }
        return $crypt;
    }


    public static function getCopyright(){
        $currentYear = date('Y');
        $stirng = self::INIT_YEAR;
        if($currentYear > self::INIT_YEAR){
            $stirng .=  '&nbsp;&ndash;&nbsp;' . $currentYear;
        }
        $stirng = '&copy; ' . $stirng . ' Denys Grybov';
        return $stirng;
    }
} 