<?php
$host = 'localhost';
$db = 'u238603173_Registro';
$user = 'u238603173_TomasVoid';
$pass = 'Leo_2812';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$fecha = $_POST['fecha'];
$vuelo = $_POST['vuelo'];
$nombre = $_POST['nombre'];
$supervisor_operacion = $_POST['supervisor_operacion'];
$grupo_mmtd = $_POST['grupo_mmtd'];
$bdp_asignado_1 = $_POST['bdp_asignado_1'];
$bdp_asignado_2 = $_POST['bdp_asignado_2'];
$personal_avianca = $_POST['personal_avianca'];
$ssss_tsa = $_POST['ssss_tsa'];
$ssss_bdp = $_POST['ssss_bdp'];
$nombres_bdp = $_POST['nombres_bdp'];
$area_novedad = $_POST['area_novedad'];
$argumento_novedad = $_POST['argumento_novedad'];
$tiempo_demora = $_POST['tiempo_demora'];
$colaboracion_av = $_POST['colaboracion_av'];
$argumento_colaboracion = $_POST['argumento_colaboracion'];

$sql = "INSERT INTO vuelos (
    fecha, vuelo, nombre, supervisor_operacion, grupo_mmtd, 
    bdp_asignado_1, bdp_asignado_2, personal_avianca, 
    ssss_tsa, ssss_bdp, nombres_bdp, area_novedad, 
    argumento_novedad, tiempo_demora, colaboracion_av, argumento_colaboracion
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Error al preparar la consulta: " . $conn->error);
}

$stmt->bind_param(
    "ssssssssisssssis",
    $fecha,
    $vuelo,
    $nombre,
    $supervisor_operacion,
    $grupo_mmtd,
    $bdp_asignado_1,
    $bdp_asignado_2,
    $personal_avianca,
    $ssss_tsa,
    $ssss_bdp,
    $nombres_bdp,
    $area_novedad,
    $argumento_novedad,
    $tiempo_demora,
    $colaboracion_av,
    $argumento_colaboracion
);

if ($stmt->execute()) {
    echo "<h2>✅ Registro de vuelo guardado exitosamente.</h2>";
    echo "<a href='vuelos.html'>← Volver al formulario de vuelos</a>";
} else {
    echo "❌ Error al guardar el registro: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
