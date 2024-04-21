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
