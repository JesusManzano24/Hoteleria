-- a conexión a la base de datos aun por hacer  --
<?php
$host = "localhost";
$usuario = "jesus";
$contrasena = "";
$basedatos = "hoteleria";

$conn = new mysqli($host, $usuario, $contrasena, $basedatos);

if ($conn->connect_error) {
  die("Error de conexión: " . $conn->connect_error);
}
?>