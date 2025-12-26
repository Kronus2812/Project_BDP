<?php
session_start();
if (isset($_SESSION['usuario'])) {
    header("Location: dashboard.php");
    exit;
}
?>

<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Login - UrbanLens</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="login-style.css"> 
  <link rel="icon" type="image/png" href="images/Logo.png">
</head>
<body>
  <div class="login-container">
    <h2>🔐 Iniciar sesión</h2>
    <a href="index.html" class="btn-retroceso">⬅ Volver al Registro</a>

    <p class="login-sub">Accede al panel de Registros</p>

    <form method="POST" action="validar_login.php">
      <label>Correo:</label>
      <input type="email" name="correo" required placeholder="ejemplo@correo.com">

      <label>Contraseña:</label>
      <input type="password" name="password" required placeholder="********">

      <button type="submit">Entrar</button>
    </form>
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
