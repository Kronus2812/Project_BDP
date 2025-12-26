<?php
$host = "localhost";
$usuario = "u238603173_TomasVoid";
$password = "Leo_2812";
$base_de_datos = "u238603173_Registro";

$conn = new mysqli($host, $usuario, $password, $base_de_datos);

if ($conn->connect_error) {
    die("❌ Conexión fallida: " . $conn->connect_error);
} else {
    echo "✅ Conexión exitosa a la base de datos.";
}

$conn->close();
?>
