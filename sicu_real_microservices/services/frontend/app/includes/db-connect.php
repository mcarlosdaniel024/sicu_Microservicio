<?php

$servername = "zephyr.proxy.rlwy.net";
$username = "root";
$password = "TMmfQayGQwaiFxvytmawtQNQDsTjekzJ";
$database = "railway";
$port = 51090;

$conn = new mysqli(
    $servername,
    $username,
    $password,
    $database,
    $port
);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

?>
