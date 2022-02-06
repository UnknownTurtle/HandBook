<?php
//header('Content-Type: text/html; charset=utf-8');
header('Content-type: application/csv');
$time = date("Ymd_His");
header("Content-Disposition: attachment; filename=report_$time.csv");
header("Content-Transfer-Encoding: UTF-8");
if ($_FILES && $_FILES["filename"]["error"] == UPLOAD_ERR_OK) {
    $name = "files/" . $_FILES["filename"]["name"];
    move_uploaded_file($_FILES["filename"]["tmp_name"], $name);
    define("n", 1000); //split the query over n rows
    $rows = 0;
    $outputData = array();
    array_push($outputData, array('Код', 'Название', 'Error'));

    require('connect.php');
    try {
        $dbh = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $user, $password);
        $queryReplace = 'REPLACE INTO handbook (id, name) VALUES ';
        if (($handle = fopen($name, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 2000, ",")) !== FALSE) {
                $num = count($data);
                $string = array();
                for ($c = 0; $c < $num; $c++) {
                    if (($c % 2) == 0) continue;

                    setlocale(LC_ALL, "ru_RU.UTF-8");
                    $say = '';
                    if (preg_match("/[^а-яёa-z0-9.-]/iu", $data[$c], $matches)) {
                        $say = 'Недопустимый символ "' . $matches[0] . '" в поле Название';
                    } else {
                        $rows++;
                        $queryReplace .= "('" . $data[$c - 1] . "', '" . $data[$c] . "')";

                        if ($rows == n) {
                            $dbh->query($queryReplace);
                            $rows = 0;
                            $queryReplace = 'REPLACE INTO handbook (id, name) VALUES ';
                        } else {
                            $queryReplace .= ", ";
                        }
                    }


                    array_push($string, $data[$c - 1], $data[$c], $say);
                    array_push($outputData, $string);
                    unset($string);

                }
            }
            if ($rows <> 0) {
                $str = substr($queryReplace, 0, -2);
                $dbh->query($str);
            }
            fclose($handle);

        }
        $dbh = null;

        $f = fopen('php://output', 'a');
        foreach ($outputData as $fields) {
            fputcsv($f, $fields);
        }

        unset($outputData);
        fclose($f);

    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }
} else echo "Erorr: file not selected";
