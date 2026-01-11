<?php


require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Entities/DataBase.php'; // Manual require to fix autoload issues

use App\Entities\DataBase;


$db = new DataBase("localhost","Vesta","root","");
$pdo = $db->connect();
$connect = $pdo;

