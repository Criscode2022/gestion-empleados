<?php

//Por motivos de seguridad, la información de la base de datos no está incluída en este código fuente subido a GitHub

$servername = "";
$username = "";
$password = "";
$dbname = "";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$username = $_POST['username'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

$sql = "INSERT INTO users (username, password) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $password);
$stmt->execute();

echo "<h1>Usuario registrado correctamente</h1>";
echo "<br>";
echo "<h2><a href='http://tusempleados.atwebpages.com/'>Volver al menú principal</a></h2>";
$stmt->close();
$conn->close();
