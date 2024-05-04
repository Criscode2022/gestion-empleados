<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newUsername = trim($_POST['username']);
    $newPassword = $_POST['password'];

    // Check if either field is empty in case of client-side validation bypass

    if (empty($newUsername) || empty($newPassword)) {
        $_SESSION['error_message'] = "Ninguno de los campos puede estar vacío.";
        header("Location: signup.php");
        exit;
    }

    // Validate username length server-side in case of client-side validation bypass

    if (strlen($newUsername) < 5 || strlen($newUsername) > 20) {
        $_SESSION['error_message'] = "El usuario debe tener entre 5 y 20 caracteres.";
        header("Location: signup.php");
        exit;
    }

    // Validate password length server-side in case of client-side validation bypass

    if (
        strlen($newPassword) < 5 || strlen($newPassword) > 20 ||
        !preg_match('/\d/', $newPassword) || !preg_match('/[A-Z]/', $newPassword)
    ) {
        $_SESSION['error_message'] = "La contraseña debe incluir al menos un número y una letra mayúscula.";
        header("Location: signup.php");
        exit;
    }

    // Check that both username and password don't have spaces. Therefore, we don't need this validation in the login form and can apply just a trim() to the input fields

    if (strpos($newUsername, ' ') !== false || strpos($newPassword, ' ') !== false) {
        $_SESSION['error_message'] = "Ni el usuario ni la contraseña pueden contener espacios.";
        header("Location: signup.php");
        exit;
    }

    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        $_SESSION['error_message'] = "Error in preparing statement: " . $conn->error;
        header("Location: signup.php");
        exit;
    }
    $stmt->bind_param("ss", $newUsername, $hashedPassword);
    try {
        $stmt->execute();
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() === 1062) {
            $_SESSION['error_message'] = "El usuario ya existe.";
        } else {
            $_SESSION['error_message'] = "Error al registrar el usuario: " . $e->getMessage();
        }
        $stmt->close();
        header("Location: signup.php");
        exit;
    }

    $_SESSION['success_message'] = "Usuario registrado correctamente.";
    $stmt->close();
    header("Location: signup.php");
    exit;
}
$conn->close();
