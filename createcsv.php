<?php
header('Content-Type: text/html; charset=utf-8');

$myList = array();
$countFields = 150000;
echo $countFields . " rows created<br>";

function genName()
{
    setlocale(LC_ALL, "ru_RU.UTF-8");
    $chars = iconv("UTF-8","windows-1251","АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯабвгдеёжзийклмнопрстуфхцчшщъыьэюяABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-.");
    $numChars = strlen($chars);
    $length = 15;
    $string = '';
    for ($i = 0; $i < $length; $i++) {
        $string .= substr($chars, rand(1, $numChars) - 1, 1);
        if (rand(0, 100) == 1) {
            $string .= interfere();
            $i++;
        }
    }

    return iconv("windows-1251", "UTF-8", $string);
    //return $string;
}

function interfere()
{
    $wrongChars = '`~!@#$%^&*()_|+=?:,';
    return substr($wrongChars, rand(1, strlen($wrongChars)) - 1, 1);
}

for ($i = 0; $i < $countFields; $i++) {
    $list = array();
    array_push($list, mt_rand(1, 1000000000), genName());
    array_push($myList, $list);
}

$fp = fopen('file.csv', 'w');

foreach ($myList as $fields) {
    fputcsv($fp, $fields);
}

fclose($fp);