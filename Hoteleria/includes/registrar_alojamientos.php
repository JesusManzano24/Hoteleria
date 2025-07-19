<?php
include("conexion.php");

$id_usuario = $_POST['id_usuario'];
$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'];
$id_tipo = $_POST['id_tipo_alojamiento'];
$id_estado = $_POST['id_estado'];
$direccion = $_POST['direccion'];
$capacidad = $_POST['capacidad'];
$precio = $_POST['precio'];
$latitud = $_POST['latitud'];
$longitud = $_POST['longitud'];

// Insertar alojamiento
$sql = "INSERT INTO alojamientos (id_usuario, nombre, id_tipo_alojamiento, direccion, descripcion, precio, capacidad, latitud, longitud, id_estado)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("isisssddii", $id_usuario, $nombre, $id_tipo, $direccion, $descripcion, $precio, $capacidad, $latitud, $longitud, $id_estado);

if ($stmt->execute()) {
  echo "Alojamiento registrado correctamente.";
} else {
  echo "Error al registrar: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>