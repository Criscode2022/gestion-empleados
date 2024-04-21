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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/gestion/src/styles/bootstrap.css">
    <link rel="stylesheet" href="/gestion/src/styles/app.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <script defer src="/gestion/src/styles/bootstrap.js"></script>
    <title>Empleados</title>
</head>

<body>

    <header>
        <img src="logo.png" alt="Logo de la aplicación en el que se lee 'Gestión Empleados'">
        <a href="/gestion/index.php" class="btn btn-primary">Cerrar sesión</a>


    </header>


    <div class="container-fluid">

        <div class="row">
            <div class="col col-md-4 d-flex flex-column gap-2 m-4">


                <h2 class="text-center m-4">Añadir empleado</h2>
                <form class="border border-dark-subtle p-4 mb-4 rounded shadow" action="manage.php" method="post">

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
                <form class="border border-dark-subtle p-4 mb-4 rounded shadow" action="manage.php" method="post" onsubmit="return confirm('¿Seguro que quieres eliminar este empleado?')">
                    <div class="mb-3">
                        <label for="delete_id" class="form-label">ID de empleado en la base de datos</label>
                        <input type="number" class="form-control" name="delete_id" min="0" required>
                    </div>
                    <button type="submit" class="btn btn-danger" name="delete">Eliminar empleado</button>
                </form>
            </div>


            <div class="col col-md-7 d-flex flex-column justify-content-start m-4">
                <h2 class="text-center m-4">Tus empleados registrados</h2>
                <div class="overflow">
                    <table class="m-auto">
                        <tr>
                            <th>ID</th>
                            <th>Nombre completo</th>
                            <th>DNI</th>
                            <th>Teléfono</th>
                            <th>Email</th>
                        </tr>
                        <?php

                        if (session_status() == PHP_SESSION_NONE) {
                            session_start();
                        }

                        error_reporting(E_ALL);
                        ini_set('display_errors', '1');

                        require $_SERVER['DOCUMENT_ROOT'] . '/gestion/vendor/autoload.php';

                        $dotenv = Dotenv\Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT'] . '/gestion');
                        $dotenv->load();


                        $servername = $_ENV['SERVERNAME'];
                        $username = $_ENV['USERNAME'];
                        $password = $_ENV['PASSWORD'];
                        $dbname = $_ENV['DBNAME'];

                        $conn = new mysqli($servername, $username, $password, $dbname);

                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }

                        $userId = $_SESSION['user_id'];
                        $sql = "SELECT * FROM employees WHERE user_id = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("i", $userId);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        while ($employee = $result->fetch_assoc()) {
                            echo "<tr><td>" . $employee['id'] . "</td><td>" . $employee['full_name'] . "</td><td>" . $employee['national_id_number'] . "</td><td>" . $employee['phone'] . "</td><td>" . $employee['email'] . "</td></tr>";
                        }

                        $stmt->close();
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>


</body>

</html>