<?php
header('Content-Type: text/html; charset=utf-8');
$myList = array();
$countFields = 100000;
if (isset($_GET['count']) && is_numeric($_GET['count'])) {
    $countFields = $_GET['count'];
} else echo "Custom value not set<br>";

function genName()
{
    $chars = iconv("UTF-8", "windows-1251", "АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯабвгдеёжзийклмнопрстуфхцчшщъыьэюяABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-.");
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
}

function interfere()
{
    $wrongChars = "`~!@#$%^&*()\"/_|+='?№%<>:,";
    return substr($wrongChars, rand(1, strlen($wrongChars)) - 1, 1);
}

for ($i = 0; $i < $countFields; $i++) {
    array_push($myList, array(mt_rand(1, 1000000000), genName()));
}

echo number_format($countFields, 0, '', ' ') . " rows created<br>";

$fp = fopen('file.csv', 'w');
foreach ($myList as $fields) {
    fputcsv($fp, $fields);
}
unset($myList);
fclose($fp);
