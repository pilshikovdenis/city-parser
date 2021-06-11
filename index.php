<?php

require_once "vendor/autoload.php";

$file = file_get_contents("https://hramy.ru/regions/city_reg.htm");

phpQuery::newDocument($file);

$table_data = pq("table > tr > td");





// строка таблицы это 5 столбцов
// 1 - город
// 3 - номер региона
// 4 - название региона
// исходя из этих данных будем вытаскивать элементы таблицы по условиям


// ПОЛУЧЕНИЕ СПИСКА ГОРОДОВ
$count = 1;
$city_list = array();
$city = "";
$region_id = 0;
foreach ($table_data as $elem) {

//    получаем город и номер региона
    if ($count == 1)
        $city = pq($elem)->text();
    if ($count == 3)
        $region_id = pq($elem)->text();


    $count++;
    if ($count == 6) {
        // сохраняем в массив полученные данные
        $city_data = compact("city", "region_id");
        array_push($city_list, $city_data);

        $count = 1;     // сброс счетчика
    }
}

// сохранение в файл
$filename = "cities.txt";
$data = serialize($city_list);
if (file_put_contents($filename, $data))
    echo "данные городов сохранены" . "<br>";
// очистка
unset($city_list, $city, $region_id, $data);


// ПОЛУЧЕНИЕ СПИСКА РЕГИОНОВ
$count = 1;
$region = "";
$region_id = 0;
$regions_list = array();

foreach ($table_data as $elem) {

//    получаем регион и номер региона
    if ($count == 4)
        $region = pq($elem)->text();
    if ($count == 3)
        $region_id = pq($elem)->text();


    $count++;
    if ($count == 6) {
        // упаковываем данные в массив
        $region_data = compact("region", "region_id");
//        если этих данных в массиве нет то сохраняем
        if (!in_array($region_data, $regions_list))
            array_push($regions_list, $region_data);

        $count = 1;     // сброс счетчика
    }
}

// сохранение в файл
$filename = "regions.txt";
$data = serialize($regions_list);
if (file_put_contents($filename, $data))
    echo "данные регионов сохранены" . "<br>";
// очистка
unset($regions_list, $region, $region_id, $data);


phpQuery::unloadDocuments();


// find("#block"), find(".table")   - ищет в коде страницы элементы, попадающие под этот селектор
