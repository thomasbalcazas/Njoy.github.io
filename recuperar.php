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

// Verificar si existe el correo
$result = $conn->query("SELECT * FROM usuario WHERE correo_electronico='$email'");
if ($result->num_rows === 0) {
    echo json_encode(["success" => false, "message" => "Correo no registrado"]);
    exit;
}

// Generar token (simple ejemplo)
$token = bin2hex(random_bytes(16));

// Aquí podrías guardarlo en la DB junto con fecha de expiración para validarlo después
$conn->query("UPDATE usuario SET token_recuperacion='$token' WHERE correo_electronico='$email'");

// Enviar correo (InfinityFree soporta mail())
$asunto = "Recuperación de contraseña - NJOY";
$mensaje = "Haz clic en el siguiente enlace para restablecer tu contraseña:\n";
$mensaje .= "https://TU_DOMINIO/reset.php?token=$token"; // 🔹 Cambia TU_DOMINIO
$cabeceras = "From: no-reply@njoy.com";

if (mail($email, $asunto, $mensaje, $cabeceras)) {
    echo json_encode(["success" => true, "message" => "Correo enviado con instrucciones"]);
} else {
    echo json_encode(["success" => false, "message" => "No se pudo enviar el correo"]);
}

$conn->close();
?>
