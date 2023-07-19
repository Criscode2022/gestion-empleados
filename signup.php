<?php
$servername = "pdb1035.awardspace.net";
$username = "4304083_autenticar";
$password = "practicas2023";
$dbname = "4304083_autenticar";

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
