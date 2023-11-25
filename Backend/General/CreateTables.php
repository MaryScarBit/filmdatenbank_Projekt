<?php
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: X-Requested-With,Origin,Content-Type,Cookie,Accept');

    $pdo = new PDO('mysql:host=localhost;dbname=test', 'root', '');
    $stmt = $pdo->prepare("CREATE DATABASE IF NOT EXISTS todo;");
    $stmt->execute();

    $pdo = new PDO('mysql:host=localhost;dbname=todo', 'root', '');

    $stmt = $pdo->prepare("CREATE TABLE IF NOT EXISTS todo ( id int not null PRIMARY KEY AUTO_INCREMENT, text varchar(250), userId int );");
    $stmt->execute();

    $stmt = $pdo->prepare("CREATE TABLE IF NOT EXISTS user ( userId int not null PRIMARY KEY AUTO_INCREMENT, email varchar(250), `password` varchar(250) );");
    $stmt->execute();
?>