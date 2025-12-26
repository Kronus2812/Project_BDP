<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$host = 'localhost';
$db = 'u238603173_Registro';
$user = 'u238603173_TomasVoid';
$pass = 'Leo_2812';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$conn->query("DELETE FROM usuarios WHERE correo = 'tomasmarinezr2006@gmail.com'");

$passwordPlano = 'admin123';
$hash = password_hash($passwordPlano, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO usuarios (nombre, correo, password) VALUES (?, ?, ?)");
if (!$stmt) {
    die("Error preparando la consulta: " . $conn->error);
}

$nombre = "Tomas Martinez";
$correo = "tomasmarinezr2006@gmail.com";

$stmt->bind_param("sss", $nombre, $correo, $hash);

if ($stmt->execute()) {
    echo "✅ Usuario re-creado correctamente con contraseña: admin123";
} else {
    echo "❌ Error al crear usuario: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
