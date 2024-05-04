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

require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT'] . '');
$dotenv->load();

$servername = $_ENV['SERVERNAME'];
$username = $_ENV['USERNAME'];
$password = $_ENV['PASSWORD'];
$dbname = $_ENV['DBNAME'];

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    exit("Connection failed: " . $conn->connect_error);
}

$action = $_POST['action'] ?? '';

if ($action === 'delete') {
    $deleteId = $_POST['delete_id'];
    $userId = $_SESSION['user_id'];

    $sql = "DELETE FROM employees WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $deleteId, $userId);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $_SESSION['success_message'] = "Empleado eliminado correctamente.";
        header("Location: employees.php");
    } else {
        $_SESSION['success_message'] = "No se ha encontrado el ID del empleado asociado a tu usuario";
        header("Location: employees.php");
    }

    $stmt->close();
} elseif ($action === 'add') {
    $userId = $_SESSION['user_id'];
    $fullName = $_POST['full_name'];
    $nationalIdNumber = $_POST['national_id_number'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];

    $sql = "INSERT INTO employees (user_id, full_name, national_id_number, phone, email) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issss", $userId, $fullName, $nationalIdNumber, $phone, $email);
    $stmt->execute();

    $_SESSION['success_message'] = "Empleado añadido correctamente.";
    header("Location: employees.php");
    $stmt->close();
} elseif ($action === 'delete_account') {
    $userId = $_SESSION['user_id'];

    // Optionally delete associated data, e.g., employees
    $sql = "DELETE FROM employees WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->close();

    // Now delete the user account
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        session_destroy();
        echo "Cuenta eliminada correctamente.";
        echo "<div style='margin: 20px;'><a href='/index.php'>Volver a la página de inicio</a></div>";
    } else {
        $_SESSION['error_message'] = "Error al eliminar la cuenta.";
        header("Location: manage.php");
    }

    $stmt->close();
}
