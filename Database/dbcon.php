<?php

$server = "localhost"; 
$username = "root";     
$password = "";         
$database = "tapnlog_final";  

// Create a connection
$conn = mysqli_connect($server, $username, $password, $database);

// Check the connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

?>