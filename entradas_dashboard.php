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
$mesFiltro = $_GET['mes'] ?? null; 

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


$sqlEntradas = "SELECT * FROM entradas WHERE $where ORDER BY fecha DESC LIMIT 50";
$resultEntradas = $conn->query($sqlEntradas);

$filasEntradas = [];
$conteoEntradasPorHora = [];
$tiemposTarde = [];
$motivosTarde = [];

if ($resultEntradas && $resultEntradas->num_rows > 0) {
    while ($row = $resultEntradas->fetch_assoc()) {
        $filasEntradas[] = $row;
        $hora = substr($row['hora_entrada'], 0, 5);
        $conteoEntradasPorHora[$hora] = ($conteoEntradasPorHora[$hora] ?? 0) + 1;
        $tiemposTarde[] = $row['tiempo_llegada_tarde'];
        $motivosTarde[] = $row['motivo_llegada_tarde'];
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard de Entradas</title>
    <link rel="stylesheet" href="diseño.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="icon" type="image/png" href="images/Logo.png">
    <style>
        .image-modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0; top: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.8);
            justify-content: center;
            align-items: center;
        }
        .image-modal img {
            max-width: 90%;
            max-height: 90%;
            border: 5px solid white;
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
        <h1>🟢 Registros de Entradas</h1>
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
                    <th>Hora Entrada</th>
                    <th>Nombre</th>
                    <th>Tiempo Llegada Tarde</th>
                    <th>Motivo Llegada Tarde</th>
                    <th>Foto Entrada</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($filasEntradas)): ?>
                    <?php foreach ($filasEntradas as $fila): ?>
                        <tr>
                            <td><?= htmlspecialchars($fila['fecha']) ?></td>
                            <td><?= htmlspecialchars($fila['hora_entrada']) ?></td>
                            <td><?= htmlspecialchars($fila['nombre']) ?></td>
                            <td><?= htmlspecialchars($fila['tiempo_llegada_tarde']) ?></td>
                            <td><?= htmlspecialchars($fila['motivo_llegada_tarde']) ?></td>
                            <td>
                                <?php if (!empty($fila['foto_entrada'])): ?>
                                    <img src="<?= htmlspecialchars($fila['foto_entrada']) ?>"
                                         alt="Foto entrada"
                                         style="max-width: 80px; border-radius: 6px; cursor: pointer;"
                                         onclick="openImageModal(this.src)">
                                <?php else: ?>
                                    Sin foto
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6">No hay registros de entrada disponibles.</td></tr>
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

<div id="imageModal" class="image-modal" onclick="closeImageModal()">
    <img id="modalImage" src="" alt="Vista previa">
</div>

<script>
const horas = <?= json_encode(array_keys($conteoEntradasPorHora)) ?>;
const conteosHoras = <?= json_encode(array_values($conteoEntradasPorHora)) ?>;
const tiemposTarde = <?= json_encode($tiemposTarde) ?>;
const motivosTarde = <?= json_encode($motivosTarde) ?>;

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
            label: 'Hora Entrada',
            data: conteosHoras,
            backgroundColor: '#29B6F6'
        }]
    },
    options: opcionesComunes
});

const tiempoCount = tiemposTarde.reduce((acc, val) => {
    acc[val] = (acc[val] || 0) + 1;
    return acc;
}, {});
new Chart(document.getElementById('chart2'), {
    type: 'pie',
    data: {
        labels: Object.keys(tiempoCount),
        datasets: [{
            data: Object.values(tiempoCount),
            backgroundColor: ['#66BB6A', '#FF7043', '#42A5F5']
        }]
    },
    options: opcionesComunes
});

const motivoCount = motivosTarde.reduce((acc, val) => {
    acc[val] = (acc[val] || 0) + 1;
    return acc;
}, {});
new Chart(document.getElementById('chart3'), {
    type: 'bar',
    data: {
        labels: Object.keys(motivoCount),
        datasets: [{
            label: 'Motivos de Llegada Tarde',
            data: Object.values(motivoCount),
            backgroundColor: '#AB47BC'
        }]
    },
    options: opcionesComunes
});

function openImageModal(src) {
    const modal = document.getElementById('imageModal');
    const modalImg = document.getElementById('modalImage');
    modalImg.src = src;
    modal.style.display = "flex";
}

function closeImageModal() {
    document.getElementById('imageModal').style.display = "none";
}
</script>
</body>
</html>
