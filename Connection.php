<?php
$servername = "mysql.cms.gre.ac.uk";
$username = "fi2157j";
$password = "password";

// Create connection
$connection = new mysqli($servername, $username, $password);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
    return false;
} 
?>