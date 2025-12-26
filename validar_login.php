<?php
session_start();

$host = 'localhost';
$db = 'u238603173_Registro';
$user = 'u238603173_TomasVoid';
$pass = 'Leo_2812';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("❌ Conexión fallida: " . $conn->connect_error);
}

$correo = $_POST['correo'];
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT id, nombre, password FROM usuarios WHERE correo = ?");
$stmt->bind_param("s", $correo);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    if (password_verify($password, $row['password'])) {
        $_SESSION['usuario'] = $row['nombre'];
        header("Location: dashboard.php");
    } else {
        echo "❌ Contraseña incorrecta";
    }
} else {
    echo "❌ Usuario no encontrado";
}
$stmt->close();
$conn->close();
?>
