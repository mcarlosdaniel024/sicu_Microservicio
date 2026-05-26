<?php
$servername = "mysql";
$username = "root";
$password = "root";
$database = "sicu_db";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
