<?php

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

if (!isset($_SESSION['user_id'])) {
  header("Location: /index.php");
  exit;
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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <link rel="stylesheet" href="/src/styles/bootstrap.css">
  <link rel="stylesheet" href="/src/styles/app.css">
  <link rel="stylesheet" href="/src/app/pages/employees/employees.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
  <script defer src="/src/styles/bootstrap.js"></script>
  <script defer src="../sign-up/signup.js"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <title>Empleados</title>

</head>

<body>

  <header>

    <img src="/src/assets/logo.png" alt="APP logo that says 'Gestión Empleados'">

  </header>


  <div class="container-fluid">


    <div class="row">
      <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
          <a class="d-md-none navbar-brand" href="#">Tus empleados</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="nav nav-tabs me-auto d-flex flex-column flex-md-row" id="myTab" role="tablist">
              <li class="nav-item" role="presentation">
                <button class="nav-link active" id="employees-tab" data-bs-toggle="tab" data-bs-target="#employees" type="button" role="tab" aria-controls="employees" aria-selected="true">Empleados</button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Gestionar empleados</button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">Eliminar cuenta</button>
              </li>
            </ul>
            <div class="container">
              <div class="row">
                <div class="col">
                  <form class="navbar-form d-flex flex-column flex-md-row gap-2 bg-transparent" role="search" method="get">
                    <input class="form-control" type="search" placeholder="Buscar por nombre o DNI" aria-label="Search" name="search" value="<?= $_GET['search'] ?? '' ?>">
                    <button class="btn btn-outline-primary" type="submit">Buscar</button>
                    <a class="btn btn-outline-danger" href="employees.php">Limpiar</a>
                    <a class="btn btn-outline-danger" href="/src/app/pages/logout/logout.php" type="submit"><span class="material-symbols-outlined">
                        logout
                      </span></a>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </nav>
    </div>

    <div class="tab-content" id="myTabContent">
      <div class="tab-pane fade show active" id="employees" role="tabpanel" aria-labelledby="employees-tab">

        <div class="col col-md-7 d-flex flex-column justify-content-start m-4">
          <div class="overflow">
            <?php
            if (session_status() == PHP_SESSION_NONE) {
              session_start();
            }

            error_reporting(E_ALL);
            ini_set('display_errors', '1');

            require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

            $dotenv = Dotenv\Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT'] . '');
            $dotenv->load();

            $conn = new mysqli($_ENV['SERVERNAME'], $_ENV['USERNAME'], $_ENV['PASSWORD'], $_ENV['DBNAME']);
            if ($conn->connect_error) {
              die("Connection failed: " . $conn->connect_error);
            }

            $userId = $_SESSION['user_id'];
            $sql = "SELECT * FROM employees WHERE user_id = ?";
            $params = [$userId];
            if (!empty($_GET['search'])) {
              $sql .= " AND (full_name LIKE ? OR national_id_number LIKE ?)";
              $searchParam = '%' . $_GET['search'] . '%';
              array_push($params, $searchParam, $searchParam);
            }

            $stmt = $conn->prepare($sql);
            $stmt->bind_param(str_repeat("s", count($params)), ...$params);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 0) {
              echo "<div class='container'>
              <div class='row'>
              <div class='col col-md-7 d-flex flex-column justify-content-start m-4'>
                <div class='message'>No hay empleados registrados.</div>
                </div>
                </div>
              </div>";
            } else {
              echo "<table>";
              echo "<tr><th><strong>ID</strong></th><th>Nombre completo</th><th>DNI</th><th>Teléfono</th><th>Email</th></tr>";

              while ($employee = $result->fetch_assoc()) {
                echo "<tr><td>" . $employee['id'] . "</td><td>" . $employee['full_name'] . "</td><td>" . $employee['national_id_number'] . "</td><td>" . $employee['phone'] . "</td><td><a href='mailto:" . $employee['email'] . "'>" . $employee['email'] . "</td></tr>";
              }

              echo "</table>";
            }

            $stmt->close();
            ?>
            </table>
          </div>
        </div>


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


      </div>

      <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">

        <div class="row">
          <div class="col col-md-4 d-flex flex-column gap-2 m-4">


            <h2 class="text-center m-4">Añadir empleado</h2>
            <form class="border border-dark-subtle p-4 mb-4 rounded shadow" action="manage.php" method="post">
              <input type="hidden" name="action" value="add">


              <div class="mb-3">
                <label for="full_name" class="form-label">Nombre completo</label>
                <input type="text" class="form-control" id="full_name" name="full_name" required>
              </div>

              <div class="mb-3">
                <label for="national_id_number" class="form-label">DNI</label>
                <input type="text" class="form-control" id="national_id_number" name="national_id_number" required>
              </div>

              <div class="mb-3">
                <label for="phone" class="form-label">Teléfono</label>
                <input type="text" class="form-control" id="phone" name="phone" required>
              </div>

              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
              </div>

              <button type="submit" class="btn btn-primary">Añadir empleado</button>
            </form>


            <h2 class="text-center m-4">Eliminar empleado</h2>
            <form id="deleteForm" class="border border-dark-subtle p-4 mb-4 rounded shadow" action="manage.php" method="post">
              <input type="hidden" name="action" value="delete">
              <div class="mb-3">
                <label for="delete_id" class="form-label">ID de empleado en la base de datos</label>
                <input type="number" class="form-control" name="delete_id" min="0" required onkeydown="return event.key != 'Enter';">
              </div>
              <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmModal">Eliminar empleado</button>
            </form>


            <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Confirmar eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    ¿Seguro que quieres eliminar este empleado?
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Eliminar</button>
                  </div>
                </div>
              </div>
            </div>

          </div>

        </div>

      </div>
      <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">

        <h2 class="text-center m-4">Eliminar cuenta</h2>


        <div class="container">
          <div class="row">

            <div class="col-md-5 m-auto">
              <form class="border border-dark-subtle p-4 mb-4 rounded shadow" action="manage.php" method="post">
                <input type="hidden" name="action" value="delete_account">
                <div class="mb-3">
                  <label for="delete_account" class="form-label">¿Seguro de que quieres eliminar tu cuenta?</label>
                  <p>Esta acción es irreversible y se eliminarán todos tus empleados asociados</p>
                </div>
                <button type="submit" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmDeleteAccountModal">Eliminar cuenta</button>
              </form>
            </div>
          </div>
        </div>

      </div>
    </div>


  </div>


  <script>
    document.querySelector('#confirmDelete').addEventListener('click', function() {
      document.querySelector('#deleteForm').submit();
    });
  </script>

</body>

</html>
