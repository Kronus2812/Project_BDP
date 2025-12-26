<?php
$correo = "camila061732@outlook.com";
$intentoPassword = "supercml02";

$mysqli = new mysqli("localhost", "u238603173_TomasVoid", "Leo_2812", "u238603173_Registro");

$stmt = $mysqli->prepare("SELECT password FROM usuarios WHERE correo = ?");
$stmt->bind_param("s", $correo);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    if (password_verify($intentoPassword, $row['password'])) {
        echo "✅ Contraseña válida";
    } else {
        echo "❌ Contraseña incorrecta";
    }
} else {
    echo "❌ Usuario no encontrado";
}

