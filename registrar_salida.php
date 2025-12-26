<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$host = 'localhost';
$db = 'u238603173_Registro';
$user = 'u238603173_TomasVoid';
$pass = 'Leo_2812';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("❌ Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fecha = $_POST["fecha"] ?? '';
    $hora_salida = $_POST["hora_salida"] ?? '';
    $nombre = $_POST["nombre"] ?? '';
    $hora_extra = $_POST["hora_extra"] ?? '';
    $motivo_extra = $_POST["motivo_extra"] ?? '';

    if (isset($_FILES["foto"]) && $_FILES["foto"]["error"] === UPLOAD_ERR_OK) {
        $foto_nombre = basename($_FILES["foto"]["name"]);
        $foto_tmp = $_FILES["foto"]["tmp_name"];
        $directorio_destino = "salidas_fotos/";

        if (!is_dir($directorio_destino)) {
            mkdir($directorio_destino, 0755, true);
        }

        $foto_final = $directorio_destino . time() . "_" . $foto_nombre;

        if (!move_uploaded_file($foto_tmp, $foto_final)) {
            die("❌ Error al guardar la imagen.");
        }
    } else {
        $foto_final = null;
    }

    $stmt = $conn->prepare("INSERT INTO salidas (fecha, hora_salida, nombre, hora_extra, motivo_extra, foto_salida)
                            VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $fecha, $hora_salida, $nombre, $hora_extra, $motivo_extra, $foto_final);

    if ($stmt->execute()) {
        echo "✅ Registro de salida exitoso.";
    } else {
        echo "❌ Error al registrar: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "⚠️ Método de acceso no permitido.";
}
?>
