<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$host = 'localhost';
$db = 'u238603173_Registro';
$user = 'u238603173_TomasVoid';
$pass = 'Leo_2812';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$vueloSeleccionado = $_GET['vuelo'] ?? 'todos';
$fechaFiltro = $_GET['fecha'] ?? null;
$mesFiltro = $_GET['mes'] ?? null;


$condiciones = [];
$parametros = [];
$tipos = '';

if ($vueloSeleccionado !== 'todos') {
    $condiciones[] = "vuelo = ?";
    $parametros[] = $vueloSeleccionado;
    $tipos .= 's';
}
if (!empty($fechaFiltro)) {
    $condiciones[] = "fecha = ?";
    $parametros[] = $fechaFiltro;
    $tipos .= 's';
}

if (!empty($mesFiltro)) {
    $condiciones[] = "fecha LIKE ?";
    $parametros[] = "$mesFiltro%";
    $tipos .= 's';
}

$whereSQL = count($condiciones) ? 'WHERE ' . implode(' AND ', $condiciones) : '';
$query = "SELECT * FROM vuelos $whereSQL ORDER BY fecha DESC LIMIT 50";
$stmt = $conn->prepare($query);
if ($tipos) {
    $stmt->bind_param($tipos, ...$parametros);
}
$stmt->execute();
$result = $stmt->get_result();

$conteoVuelosPorFecha = [];
$colaboracionesAV = [];
$ssssBDP = [];
$ssssTSA = [];

$registros = [];
while ($row = $result->fetch_assoc()) {
    $registros[] = $row;
    $fecha = $row['fecha'];
    $conteoVuelosPorFecha[$fecha] = ($conteoVuelosPorFecha[$fecha] ?? 0) + 1;
    $colaboracionesAV[] = $row['colaboracion_av'];
    $ssssBDP[] = $row['ssss_bdp'];
    $ssssTSA[] = $row['ssss_tsa'];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard de Vuelos</title>
    <link rel="stylesheet" href="vuelos_dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="icon" type="image/png" href="images/Logo.png">
</head>
<body>
<header class="header">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <a href="dashboard.php" class="btn">← Volver al Panel Principal</a>
            <a href="logout.php" class="btn logout" style="margin-left: 10px;">🔒 Cerrar sesión</a>
        </div>
        <h1>✈️ Registros de Vuelos</h1>
    </div>
</header>

<main>
    <form method="GET" class="filter-form">
        <label for="vuelo">Filtrar por Vuelo:</label>
        <select name="vuelo" id="vuelo">
            <option value="todos">-- Todos --</option>
            <?php
            $vuelosOpciones = [
                "MIA - AV0006", "MIA - AV0126", "IAD - AV0246", "MCO - AV0028", "FLL - AV0036",
                "SJU - AV0258", "JFK - AV0244", "MIA - AV0004", "JFK - AV0210", "MIA - AV0008",
                "JFK - AV0020", "BOS - AV0222", "BOS - AV0228", "MCO - AV0216", "SJU - AV0214",
                "BOS - AV0226", "IAD - AV0148", "ORD - AV0262", "TPA - AV0194", "DFW - AV0188"
            ];
            foreach ($vuelosOpciones as $vuelo) {
                $selected = ($vuelo === $vueloSeleccionado) ? "selected" : "";
                echo "<option value=\"$vuelo\" $selected>$vuelo</option>";
            }
            ?>
        </select>

        <label for="fecha">Filtrar por Fecha:</label>
        <input type="date" name="fecha" id="fecha" value="<?= htmlspecialchars($fechaFiltro) ?>">

        <button type="submit">🔍 Filtrar</button>
        <form method="GET">
    <label for="mes">Filtrar por mes:</label>
<input type="month" name="mes" id="mes" class="input-mes" value="<?= htmlspecialchars($_GET['mes'] ?? '') ?>">

    <button type="submit">Filtrar</button>

    </form>

    <div class="table-container">
        <table class="tabla-vuelos">
            <thead>
                <tr>
                    <th>Fecha</th><th>Vuelo</th><th>Nombre</th><th>Supervisor Operación</th>
                    <th>Grupo MMTD</th><th>BDP Asignado 1</th><th>BDP Asignado 2</th><th>Personal Avianca</th>
                    <th>SSSS TSA</th><th>SSSS BDP</th><th>Nombres BDP</th><th>Área Novedad</th>
                    <th>Argumento Novedad</th><th>Tiempo Demora</th><th>Colaboración AV</th><th>Argumento Colaboración</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($registros as $vuelo): ?>
                <tr>
                    <td><?= $vuelo['fecha']; ?></td>
                    <td><?= $vuelo['vuelo']; ?></td>
                    <td><?= $vuelo['nombre']; ?></td>
                    <td><?= $vuelo['supervisor_operacion']; ?></td>
                    <td><?= $vuelo['grupo_mmtd']; ?></td>
                    <td><?= $vuelo['bdp_asignado_1']; ?></td>
                    <td><?= $vuelo['bdp_asignado_2']; ?></td>
                    <td><?= $vuelo['personal_avianca']; ?></td>
                    <td><?= $vuelo['ssss_tsa']; ?></td>
                    <td><?= $vuelo['ssss_bdp']; ?></td>
                    <td><?= $vuelo['nombres_bdp']; ?></td>
                    <td><?= $vuelo['area_novedad']; ?></td>
                    <td><?= $vuelo['argumento_novedad']; ?></td>
                    <td><?= $vuelo['tiempo_demora']; ?></td>
                    <td><?= $vuelo['colaboracion_av']; ?></td>
                    <td><?= $vuelo['argumento_colaboracion']; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <section class="chart-container">
        <div class="chart-box"><canvas id="chart1"></canvas></div>
        <div class="chart-box"><canvas id="chart2"></canvas></div>
        <div class="chart-box"><canvas id="chart3"></canvas></div>
        <div class="chart-box"><canvas id="chart4"></canvas></div>
    </section>
</main>

<script>
const fechas = <?= json_encode(array_keys($conteoVuelosPorFecha)) ?>;
const conteos = <?= json_encode(array_values($conteoVuelosPorFecha)) ?>;
const colaboraciones = <?= json_encode($colaboracionesAV) ?>;
const ssssBDP = <?= json_encode($ssssBDP) ?>;
const ssssTSA = <?= json_encode($ssssTSA) ?>;

const opcionesComunes = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            labels: { color: '#fff' }
        }
    }
};

new Chart(document.getElementById('chart1'), {
    type: 'bar',
    data: {
        labels: fechas,
        datasets: [{
            label: 'CANTIDAD DE VUELOS',
            data: conteos,
            backgroundColor: '#29B6F6'
        }]
    },
    options: opcionesComunes
});

const colabCount = colaboraciones.reduce((acc, val) => {
    acc[val] = (acc[val] || 0) + 1;
    return acc;
}, {});
new Chart(document.getElementById('chart2'), {
    type: 'pie',
    data: {
        labels: Object.keys(colabCount),
        datasets: [{
            label: 'Colaboración AV',
            data: Object.values(colabCount),
            backgroundColor: ['#66BB6A', '#FF7043', '#42A5F5', '#FDD835'],
            borderColor: '#fff',
            borderWidth: 2
        }]
    },
    options: {
        ...opcionesComunes,
        plugins: {
            legend: { display: false },
            title: {
                display: true,
                text: 'Colaboración con AV',
                color: '#fff',
                font: { size: 16 }
            }
        }
    }
});

const ssssBDPCount = ssssBDP.reduce((acc, val) => {
    acc[val] = (acc[val] || 0) + 1;
    return acc;
}, {});
new Chart(document.getElementById('chart3'), {
    type: 'bar',
    data: {
        labels: Object.keys(ssssBDPCount),
        datasets: [{
            label: 'SSSS BDP',
            data: Object.values(ssssBDPCount),
            backgroundColor: '#AB47BC'
        }]
    },
    options: opcionesComunes
});

const ssssTSACount = ssssTSA.reduce((acc, val) => {
    acc[val] = (acc[val] || 0) + 1;
    return acc;
}, {});
new Chart(document.getElementById('chart4'), {
    type: 'bar',
    data: {
        labels: Object.keys(ssssTSACount),
        datasets: [{
            label: 'SSSS TSA',
            data: Object.values(ssssTSACount),
            backgroundColor: '#FFA726'
        }]
    },
    options: opcionesComunes
});
</script>
</body>
</html>


