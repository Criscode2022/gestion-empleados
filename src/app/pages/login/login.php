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
  exit("Error de conexión: " . $conn->connect_error);
}

if (isset($_POST['username']) && isset($_POST['password'])) {
  $username = trim($_POST['username']);
  $password = trim($_POST['password']);

  // Check if either field is empty in case of client-side validation bypass
  if (empty($username) || empty($password)) {
    $_SESSION['error_message'] = "Ninguno de los campos puede estar vacío.";
    header("Location: /index.php");
    exit;
  }

  // SQL injection prevention
  $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();

  $result = $stmt->get_result();
  $user = $result->fetch_assoc();


  if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    header("Location: /src/app/pages/employees/employees.php");
    exit;
  } else {
    $_SESSION['error_message'] = "Usuario o contraseña incorrecta";
    header("Location: /index.php");
    exit;
  }

  $stmt->close();
}

$conn->close();
