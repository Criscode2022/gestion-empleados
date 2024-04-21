<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

error_reporting(E_ALL);
ini_set('display_errors', '1');

?>

<!DOCTYPE html>
<html lang="es">

<head>

  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestión Empleados</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <link rel="stylesheet" href="src/styles/bootstrap.css">
  <link rel="stylesheet" href="src/styles/app.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <script defer src="src/styles/bootstrap.js"></script>
  <script defer src="src/utils/password-toggle.js"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>


</head>

<body>


  <header class="border-bottom">

    <img src="/gestion/src/assets/logo.png" alt="APP logo that says 'Gestión Empleados'">

  </header>


  <div class="container">

    <div class="col">
      <div class="row p-4">
        <h1 class="text-center">Iniciar sesión</h1>
      </div>
    </div>

    <div class="row">
      <div class="col-md-5 m-auto">
        <form class="border border-dark-subtle p-4 rounded shadow" action="src/app/pages/login/login.php" method="post">

          <div class="mb-3">
            <label for="username" class="form-label">Usuario</label>
            <input type="text" minlength="5" maxlength="20" class="form-control" name="username" placeholder="Usuario" required>
          </div>

          <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" minlength="5" maxlength="20" class="form-control" name="password" id="password" placeholder="Contraseña" required>
            <i class="bi bi-eye-slash" id="togglePassword"></i>
          </div>
          <?php
          if (isset($_SESSION['error_message'])) {
            echo "<h5 class='text-danger'>" . $_SESSION['error_message'] . "</h5>";
            unset($_SESSION['error_message']);
          }
          ?>
          <div class="container text-center"><button type="submit" class="btn btn-primary">Entrar</button>

          </div>
        </form>
      </div>
    </div>

    <div class="row">

      <div class="col d-flex justify-content-center align-items-center gap-4 p-5">
        <strong>¿No tienes cuenta?</strong>
        <a href="src/app/pages/sign-up/signup.php" class="btn btn-primary">Registrarse</a>

      </div>
    </div>

  </div>

</body>

</html>