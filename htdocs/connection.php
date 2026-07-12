<?php

$host = "";      
$dbname = ""; 
$username = "";       
$password = "";           


$conn = new mysqli($host, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

ob_start();
?>
