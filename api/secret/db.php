<?php

$host     = "db";
$user     = "root";
$pass     = "root";
$dbname   = "urbanature";

// $host     = "localhost";
// $user     = "totosham_urba";
// $pass     = "W@87Ew47MZ7W";
// $dbname   = "totosham_urbanature";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo $e->getMessage();                         
}

