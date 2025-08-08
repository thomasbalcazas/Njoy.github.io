<?php
header('Content-Type: application/json');

// Datos de conexión MySQL InfinityFree
$host = "sql208.infinityfree.com";
$user = "if0_39450468";
$pass = "Njoy2025";
$db   = "if0_39450468_XXX"; // 🔹 Cambia XXX por el nombre real de tu DB

// Conectar
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Error de conexión a la base de datos"]);
    exit;
}

// Leer datos JSON del fetch()
$input = json_decode(file_get_contents("php://input"), true);
$nombre = $conn->real_escape_string($input['nombre']);
$email = $conn->real_escape_string($input['email']);
$password = $input['password'];

// Validar si ya existe el email
$check = $conn->query("SELECT id_usuario FROM usuario WHERE correo_electronico='$email'");
if ($check->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "El email ya está registrado"]);
    exit;
}

// Guardar usuario con contraseña encriptada
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$sql = "INSERT INTO usuario (nombre_apellido, correo_electronico, contrasena, fecha_registro, rol) 
        VALUES ('$nombre', '$email', '$hashedPassword', NOW(), 'usuario')";

if ($conn->query($sql) === TRUE) {
    echo json_encode(["success" => true, "message" => "Registro exitoso"]);
} else {
    echo json_encode(["success" => false, "message" => "Error: " . $conn->error]);
}

$conn->close();
?>
