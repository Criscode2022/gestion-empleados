<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');

$servername = "pdb1035.awardspace.net";
$username = "4304083_autenticar";
$password = "practicas2023";
$dbname = "4304083_autenticar";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

if (isset($_POST['username']) && isset($_POST['password'])) {
    // Prevención de SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");

    $stmt->bind_param("s", $_POST['username']);

    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Verificar la contraseña 
    if ($user && password_verify($_POST['password'], $user['password'])) {
        $_SESSION['user_id'] = $user['id'];  // Guardar en la sesión el ID del usuario una vez autenticado

        // Redireccionar a empleados.php
        header("Location: empleados.php");
        exit;
    } else {
        echo "<h1>Usuario o contraseña incorrecta</h1>";
        echo "<h2><a href='http://tusempleados.atwebpages.com/'>Volver al menú principal</a></h2>";
    }

    $stmt->close();
}

$conn->close();
