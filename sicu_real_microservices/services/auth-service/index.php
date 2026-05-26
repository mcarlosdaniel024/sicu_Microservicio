
<?php
header('Content-Type: application/json');

$conn = new mysqli("mysql", "root", "root", "sicu_db");

if ($conn->connect_error) {
    die(json_encode(["error"=>"DB error"]));
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $usuario = $_POST['usuario'] ?? '';
    $password = $_POST['password'] ?? '';

    echo json_encode([
        "status"=>"ok",
        "message"=>"Servicio de autenticación activo",
        "usuario"=>$usuario
    ]);
}else{
    echo json_encode([
        "service"=>"SICU Auth Service",
        "status"=>"running"
    ]);
}
?>
