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
  <title>Registrarse</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <link rel="stylesheet" href="/gestion/src/styles/bootstrap.css">
  <link rel="stylesheet" href="/gestion/src/styles/app.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <script defer src="/gestion/src/styles/bootstrap.js"></script>
  <script defer src="/gestion/src/utils/password-toggle.js"></script>
  <script defer src="signup.js"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
</head>

<body>


  <header class="border-bottom">

    <img src="/gestion/src/assets/logo.png" alt="Logo de la aplicación en el que se lee 'Gestión Empleados'">


  </header>

  <div class="container">
    <?php if (isset($_SESSION['success_message'])) : ?>
      <div class="toast-container position-fixed top-0 start-50 translate-middle-x p-3" id="toastPlacement" aria-live="polite" aria-atomic="true" style="z-index: 11;">
        <div class="toast" data-bs-autohide="true" data-bs-delay="5000">
          <div class="toast-header">
            <strong class="me-auto">Gestión de Empleados</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
          </div>
          <div class="toast-body">
            <?= $_SESSION['success_message'] ?>
          </div>
        </div>
      </div>
      <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>
  </div>


  <div class="container">

    <div class="row p-4">
      <div class="col">
        <h1 class="text-center">Registrarse</h1>
      </div>
    </div>

    <div class="row">
      <div class="col-md-5 m-auto">
        <form class="border border-dark-subtle p-4 rounded shadow" action="register.php" method="post">

          <div class="mb-3">
            <label for="username" class="form-label">Usuario</label>
            <input type="text" minlength="5" maxlength="20" class="form-control" id="username" name="username" placeholder="Usuario" required>
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

          <div class="container text-center">

            <button type="submit" class="btn btn-primary px-4" style="position: relative; left: 10px;">Registrarse</button>

          </div>


        </form>
      </div>
    </div>

    <div class="row">
      <div class="col d-flex justify-content-center align-items-center gap-4 p-5">

        <strong>¿Ya tienes cuenta?</strong>

        <a href="/gestion/index.php" class="btn btn-primary">Iniciar sesión</a>

      </div>
    </div>



</body>



</html>