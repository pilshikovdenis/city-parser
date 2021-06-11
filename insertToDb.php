<?php

// данные для подключения к бд
$host = "localhost";
$user = "root";
$password = "root";
$database = "agropanel";

// подключение
$link = mysqli_connect($host, $user, $password, $database)
    or die("Ошибка подключения: " . mysqli_error($link));


//ЗАГРУЗКА РЕГИОНОВ
// загрузка данных из файла
$filename = "regions.txt";
$data = file_get_contents($filename);
$regions_list = unserialize($data);

foreach ($regions_list as $item)
{
    $id = $item["region_id"];
    $name = $item["region"];

    $result = insert_region($link, $id, $name);
    if (!$result) echo "Ошибка " . mysqli_error($link) . "<br>";
}
//var_dump($regions_list);

unset($data, $regions_list);

//Загрузка ГОРОДОВ
//Загрузка данных из файла
$filename = "cities.txt";
$data = file_get_contents($filename);
$cities_list = unserialize($data);


foreach ($cities_list as $item)
{

    $name = $item["city"];
    $region_id = $item["region_id"];

    $result = insert_city($link, $name, $region_id);
    if (!$result) echo "Ошибка " . mysqli_error($link) . "<br>";
}

mysqli_close($link);

function insert_region($link, $id, $name)
{
    $query = "INSERT INTO `region`(`id`, `name`) VALUES ({$id},'{$name}')";
    return mysqli_query($link, $query);

}

function insert_city($link, $name, $region_id)
{
    $query = "INSERT INTO `city`(`name`, `region_id`) VALUES ('{$name}',{$region_id})";
    return mysqli_query($link, $query);
}