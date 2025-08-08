<?php
header('Content-Type: application/json');

$host = "sql208.infinityfree.com";
$user = "if0_39450468";
$pass = "Njoy2025";
$db   = "if0_39450468_njoy"; // 🔹 Cambia XXX por el nombre real

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Error de conexión a la base de datos"]);
    exit;
}

$input = json_decode(file_get_contents("php://input"), true);
$email = $conn->real_escape_string($input['email']);
$password = $input['password'];

// Buscar usuario
$result = $conn->query("SELECT * FROM usuario WHERE correo_electronico='$email'");
if ($result->num_rows === 0) {
    echo json_encode(["success" => false, "message" => "Correo no registrado"]);
    exit;
}

$userData = $result->fetch_assoc();

// Verificar contraseña
if (password_verify($password, $userData['contrasena'])) {
    echo json_encode([
        "success" => true,
        "usuario" => [
            "id" => $userData['id_usuario'],
            "nombre" => $userData['nombre_apellido'],
            "email" => $userData['correo_electronico']
        ]
    ]);
} else {
    echo json_encode(["success" => false, "message" => "Contraseña incorrecta"]);
}

$conn->close();
?>
