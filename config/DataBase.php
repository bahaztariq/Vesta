<?php


require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Entities/DataBase.php'; // Manual require to fix autoload issues

use App\Entities\DataBase;


$db = DataBase::getInstance("mysql:host=localhost;dbname=Vesta;charset=utf8", "root", "");
$pdo = $db->getConnection();
$connect = $pdo;

