<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

$host = 'localhost';
$db = 'u238603173_Registro';
$user = 'u238603173_TomasVoid';
$pass = 'Leo_2812';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$where = "1";
$nombreFiltro = $_GET['nombre'] ?? '';
$fechaFiltro = $_GET['fecha'] ?? '';
$mesFiltro = $_GET['mes'] ?? '';


if (!empty($nombreFiltro)) {
    $where .= " AND nombre='" . $conn->real_escape_string($nombreFiltro) . "'";
}
if (!empty($fechaFiltro)) {
    $where .= " AND fecha='" . $conn->real_escape_string($fechaFiltro) . "'";
}
if (!empty($mesFiltro)) {
    $mesSeguro = $conn->real_escape_string($mesFiltro);
    $where .= " AND fecha LIKE '{$mesSeguro}%'";
}



$sqlSalidas = "SELECT * FROM salidas WHERE $where ORDER BY fecha DESC LIMIT 50";
$resultSalidas = $conn->query($sqlSalidas);

$filasSalidas = [];
$conteoSalidasPorHora = [];
$horasExtra = [];
$motivosExtra = [];

if ($resultSalidas && $resultSalidas->num_rows > 0) {
    while ($row = $resultSalidas->fetch_assoc()) {
        $filasSalidas[] = $row;
        $hora = substr($row['hora_salida'], 0, 5);
        $conteoSalidasPorHora[$hora] = ($conteoSalidasPorHora[$hora] ?? 0) + 1;
        $horasExtra[] = $row['hora_extra'] ?: 'No';
        $motivosExtra[] = $row['motivo_extra'] ?: '-';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard de Salidas</title>
    <link rel="stylesheet" href="diseño.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="icon" type="image/png" href="images/Logo.png">
    <style>
        .image-thumbnail {
            max-width: 80px;
            border-radius: 6px;
            cursor: pointer;
        }
        #modal {
            display: none;
            position: fixed;
            z-index: 999;
            left: 0; top: 0;
            width: 100%; height: 100%;
            background-color: rgba(0,0,0,0.8);
        }
        #modal img {
            display: block;
            max-width: 90%;
            max-height: 90%;
            margin: 40px auto;
            border-radius: 8px;
        }
    </style>
</head>
<body>
<header class="header">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <a href="dashboard.php" class="btn">← Volver al Panel Principal</a>
            <a href="logout.php" class="btn logout" style="margin-left: 10px;">🔒 Cerrar sesión</a>
        </div>
        <h1>🔴 Registros de Salidas</h1>
    </div>
</header>

<main>
<form method="GET" class="filter-form">
        <select name="nombre">
            <option value="">-- Filtrar por nombre --</option>
            <option>Jose Gregorio Valencia Garcia</option>
            <option>Marcela Herrera Cardona</option>
            <option>Johan Stiven Matiz Russi</option>
            <option>Leyla Ruth Rodriguez Pores</option>
            <option>Jeisson Camilo Cardenas Sanchez</option>
            <option>Sebastian Romero Laguna</option>
            <option>Jhon Didier Lopez Cardenas</option>
            <option>Daniela Lugo Mosquera</option>
            <option>Luzby Julieth Cespedes Garcia</option>
            <option>Diana Hernandez Sierra</option>
            <option>Ariel Enrique Castellar Pava</option>
            <option>Andres Mauricio Rojas Vargas</option>
            <option>Brayan Daniel Porras Ramirez</option>
            <option>Claudia Marcela Flores Aranguren</option>
            <option>Karen Vanessa Mejia Calderon</option>
            <option>Lesly Viviana Torres Porras</option>
            <option>Nicolle Vanesa Molina Madrigal</option>
            <option>Vanessa Vasquez Franco</option>
            <option>Brenda Yulieth Garavito Moreno</option>
            <option>Sandra Milena Amaris Manjarres</option>
            <option>Angie Natalia Ballen Galeano</option>
            <option>Leidy Bosiga</option>
            <option>Deivis Cardenas Perez</option>
            <option>José Armando García Urrea</option>
        </select>
        <input type="date" name="fecha" value="<?= htmlspecialchars($fechaFiltro) ?>">
    
    <label for="mes">Filtrar por mes:</label>
<input type="month" name="mes" id="mes" class="input-mes" value="<?= htmlspecialchars($_GET['mes'] ?? '') ?>">
    
<button type="submit" class="btn">Buscar</button>
</form>
    </form>

    <div class="table-container">
        <table class="tabla-vuelos">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Hora Salida</th>
                    <th>Nombre</th>
                    <th>Hora Extra</th>
                    <th>Motivo Extra</th>
                    <th>Foto Salida</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($filasSalidas)): ?>
                    <?php foreach ($filasSalidas as $fila): ?>
                        <tr>
                            <td><?= htmlspecialchars($fila['fecha']) ?></td>
                            <td><?= htmlspecialchars($fila['hora_salida']) ?></td>
                            <td><?= htmlspecialchars($fila['nombre']) ?></td>
                            <td><?= htmlspecialchars($fila['hora_extra'] ?: 'No') ?></td>
                            <td><?= htmlspecialchars($fila['motivo_extra'] ?: '-') ?></td>
                            <td>
                                <?php if (!empty($fila['foto_salida'])): ?>
                                    <img src="<?= htmlspecialchars($fila['foto_salida']) ?>" class="image-thumbnail" onclick="openImageModal('<?= htmlspecialchars($fila['foto_salida']) ?>')">
                                <?php else: ?>
                                    Sin foto
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6">No hay registros de salida disponibles.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <section class="chart-container">
        <div class="chart-box"><canvas id="chart1"></canvas></div>
        <div class="chart-box"><canvas id="chart2"></canvas></div>
        <div class="chart-box"><canvas id="chart3"></canvas></div>
    </section>
</main>

<div id="modal" onclick="this.style.display='none'">
    <img id="modalImage" src="">
</div>

<script>
function openImageModal(src) {
    document.getElementById('modalImage').src = src;
    document.getElementById('modal').style.display = 'block';
}

const horas = <?= json_encode(array_keys($conteoSalidasPorHora)) ?>;
const conteosHoras = <?= json_encode(array_values($conteoSalidasPorHora)) ?>;
const horasExtra = <?= json_encode($horasExtra) ?>;
const motivosExtra = <?= json_encode($motivosExtra) ?>;

const opcionesComunes = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: { labels: { color: '#fff' } }
    }
};

new Chart(document.getElementById('chart1'), {
    type: 'bar',
    data: {
        labels: horas,
        datasets: [{
            label: 'Hora de Salida',
            data: conteosHoras,
            backgroundColor: '#FF7043'
        }]
    },
    options: opcionesComunes
});

const horaExtraCount = horasExtra.reduce((acc, val) => {
    acc[val] = (acc[val] || 0) + 1;
    return acc;
}, {});
new Chart(document.getElementById('chart2'), {
    type: 'pie',
    data: {
        labels: Object.keys(horaExtraCount),
        datasets: [{
            data: Object.values(horaExtraCount),
            backgroundColor: ['#26A69A', '#FFA726', '#7E57C2']
        }]
    },
    options: opcionesComunes
});

const motivoExtraCount = motivosExtra.reduce((acc, val) => {
    acc[val] = (acc[val] || 0) + 1;
    return acc;
}, {});
new Chart(document.getElementById('chart3'), {
    type: 'bar',
    data: {
        labels: Object.keys(motivoExtraCount),
        datasets: [{
            label: 'Motivos de Hora Extra',
            data: Object.values(motivoExtraCount),
            backgroundColor: '#66BB6A'
        }]
    },
    options: opcionesComunes
});
</script>
</body>
</html>
