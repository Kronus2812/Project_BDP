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
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Dashboard - UrbanLens</title>
  <link rel="stylesheet" href="dashboard.css">
  <link rel="icon" type="image/png" href="images/Logo.png">
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(135deg, #1c1c1c, #2b2b2b);
      color: #f5f5f5;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }

    .dashboard-container {
      background-color: rgba(255, 255, 255, 0.03);
      padding: 3rem;
      border-radius: 20px;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.5);
      max-width: 600px;
      width: 90%;
      text-align: center;
    }

    .dashboard-container h1 {
      font-size: 2.5rem;
      margin-bottom: 0.5rem;
      color: #ff5252;
    }

    .dashboard-container p {
      font-size: 1.1rem;
      color: #ccc;
      margin-bottom: 2rem;
    }

    .botones {
      display: flex;
      flex-direction: column;
      gap: 15px;
      align-items: center;
    }

    .botones a {
      background: linear-gradient(135deg, #1976d2, #0d47a1);
      color: white;
      text-decoration: none;
      padding: 12px 20px;
      border-radius: 25px;
      font-weight: bold;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
      transition: all 0.3s ease;
      width: 100%;
      max-width: 300px;
      text-align: center;
    }

    .botones a:hover {
      background: linear-gradient(135deg, #0d47a1, #1976d2);
      transform: translateY(-2px);
    }

    .botones a.cerrar {
      background: linear-gradient(135deg, #ff3d00, #bf360c);
    }

    .botones a.cerrar:hover {
      background: linear-gradient(135deg, #bf360c, #ff3d00);
    }

    @media (max-width: 480px) {
      .dashboard-container {
        padding: 2rem;
      }

      .dashboard-container h1 {
        font-size: 2rem;
      }
    }
  </style>
</head>
<body>

  <div class="dashboard-container">
    <h1>¡Bienvenid@ <?= $_SESSION['usuario']; ?>!</h1>
    <p>Has ingresado exitosamente al panel de control de <strong>BDP</strong> 🚀</p>

    <div class="botones">
      <a href="vuelos_dashboard_completo.php">📊 Dashboard Vuelos</a>
      <a href="entradas_dashboard.php">🛬 Dashboard Entradas</a>
      <a href="salidas_dashboard.php">🛫 Dashboard Salidas</a>
      <a href="logout.php" class="cerrar">🔒 Cerrar Sesión</a>
    </div>
  </div>
  
<footer style="
  position: fixed;
  bottom: 0;
  left: 0;
  width: 100%;
  background-color: #1a1a1a;
  color: #ffffff;
  text-align: center;
  padding: 8px 10px;
  font-size: 13px;
  z-index: 100;
">
  &copy; 2025 BEHAVIOR DETECTION PROGRAM - Desarrollado por Tomas Martinez - Leyla Rodriguez
</footer>

</body>
</html>


