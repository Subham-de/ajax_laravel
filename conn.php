<?php
$server_name = "localhost";
$username = "root";
$password = "";
$data_base = "demoquiz";

// Create connection
$conn = new mysqli($server_name, $username, $password, $data_base);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
