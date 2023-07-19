<?php
session_start();

//Por motivos de seguridad, la información de la base de datos no está incluída en este código fuente subido a GitHub

$servername = "";
$username = "";
$password = "";
$dbname = "";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Comprobar si se envió la información desde el formulario e introducir los datos en el cuerpo de la petición
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['delete'])) {
        $deleteId = $_POST['delete_id'];
        $userId = $_SESSION['user_id'];

        $sql = "DELETE FROM employees WHERE id = ? AND user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $deleteId, $userId);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "Empleado eliminado correctamente";
        } else {
            echo "No se ha encontrado el ID del empleado asociado a tu usuario";
        }

        $stmt->close();
    } else {
        $userId = $_SESSION['user_id'];
        $fullName = $_POST['full_name'];
        $nationalIdNumber = $_POST['national_id_number'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];

        $sql = "INSERT INTO employees (user_id, full_name, national_id_number, phone, email) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issss", $userId, $fullName, $nationalIdNumber, $phone, $email);
        $stmt->execute();

        echo "Empleado añadido correctamente";

        $stmt->close();
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap.css">
    <link rel="stylesheet" href="app.css">
    <title>Empleados</title>
</head>

<body>
    <img src="logo.png" alt="Logo de la aplicación en el que se lee 'Gestión Empleados'">

    <header>
        <h2>Añadir empleado</h2>
    </header>
    <div id="close">
        <a href="/index.html" class="btn btn-primary">Cerrar sesión</a>
    </div>

    <h2 class="empleados">Añadir empleado</h2>

    <form action="" method="post">
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
        <button id="centrar1" type="submit" class="btn btn-primary">Añadir empleado</button>
    </form>

    <h2 class="empleados">Eliminar empleado</h2>
    <form action="" method="post" onsubmit="return confirm('¿Seguro que quieres eliminar este empleado?')">
        <div class="mb-3">
            <label for="delete_id" class="form-label">ID de empleado en la base de datos</label>
            <input type="number" class="form-control" id="delete_id" name="delete_id" min="0" required>
        </div>
        <button id="centrar2" type="submit" class="btn btn-danger" name="delete">Eliminar empleado</button>
    </form>


    <h2 class="empleados">Tus empleados registrados</h2>
    <div class="table-responsive">
        <table id="employeeList">
            <tr>
                <th>ID en la base de datos</th>
                <th>Nombre completo</th>
                <th>DNI</th>
                <th>Teléfono</th>
                <th>Email</th>
            </tr>
            <?php
            $userId = $_SESSION['user_id'];  // Obtener el ID del usuario desde la sesión

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

</body>

</html>