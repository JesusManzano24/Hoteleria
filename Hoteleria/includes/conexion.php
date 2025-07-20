<?php
$host      = 'localhost';
$usuario   = 'root';
$contrasena= '';
$basedatos = 'hoteleria';

$conn = new mysqli($host, $usuario, $contrasena, $basedatos);

if ($conn->connect_error) {
  die("Error de conexión: " . $conn->connect_error);
}
?>