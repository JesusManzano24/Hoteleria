<?php
// iniciar sesión si vas a usar $_SESSION['id_usuario']
session_start();

// conexión a la BD (ajusta la ruta si usas carpetas)
include("../includes/conexion.php");

// validación básica para evitar errores si falta algo
if (
  isset($_POST['id_usuario'], $_POST['nombre'], $_POST['descripcion'], $_POST['id_tipo_alojamiento'],
        $_POST['id_estado'], $_POST['direccion'], $_POST['capacidad'],
        $_POST['precio'], $_POST['latitud'], $_POST['longitud'])
) {
  // recibir datos
  $id_usuario = intval($_POST['id_usuario']);
  $nombre = trim($_POST['nombre']);
  $descripcion = trim($_POST['descripcion']);
  $id_tipo = intval($_POST['id_tipo_alojamiento']);
  $id_estado = intval($_POST['id_estado']);
  $direccion = trim($_POST['direccion']);
  $capacidad = intval($_POST['capacidad']);
  $precio = floatval($_POST['precio']);
  $latitud = floatval($_POST['latitud']);
  $longitud = floatval($_POST['longitud']);

  // insertar alojamiento en la BD
  $sql = "INSERT INTO alojamientos (id_usuario, nombre, id_tipo_alojamiento, direccion, descripcion, precio, capacidad, latitud, longitud, id_estado)
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

  $stmt = $conn->prepare($sql);
  if ($stmt) {
    $stmt->bind_param("isisssddii", $id_usuario, $nombre, $id_tipo, $direccion, $descripcion, $precio, $capacidad, $latitud, $longitud, $id_estado);
    if ($stmt->execute()) {
      echo "✅ Alojamiento registrado correctamente.";
    } else {
      echo "❌ Error al registrar: " . $stmt->error;
    }
    $stmt->close();
  } else {
    echo "❌ Error en la preparación: " . $conn->error;
  }

  $conn->close();
} else {
  echo "❌ Faltan campos obligatorios.";
}
?>