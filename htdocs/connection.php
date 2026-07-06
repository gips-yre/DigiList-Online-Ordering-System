<?php

$host = "sql211.infinityfree.com";      
$dbname = "if0_37366487_Evangelista_online_ordering_system"; 
$username = "if0_37366487";       
$password = "nv0PWGrmw4mJpC";           


$conn = new mysqli($host, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

ob_start();
?>
