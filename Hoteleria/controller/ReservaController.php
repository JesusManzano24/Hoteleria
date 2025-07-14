<?php
include("../includes/db.php");
session_start();

if (isset($_POST['reservar'])) {
  $usuario_id = $_SESSION['usuario_id'];
  $hotel_id = $_POST['hotel_id'];
  $entrada = $_POST['fecha_entrada'];
  $salida = $_POST['fecha_salida'];

  // Validación básica
  if ($entrada >= $salida) {
    $_SESSION['mensaje'] = "La fecha de salida debe ser posterior a la de entrada.";
    header("Location: ../views/detalle.php?id=" . $hotel_id);
    exit();
  }

  $query = "INSERT INTO reservas (usuario_id, hotel_id, fecha_entrada, fecha_salida) 
            VALUES ('$usuario_id', '$hotel_id', '$entrada', '$salida')";

  if (mysqli_query($conn, $query)) {
    $_SESSION['mensaje'] = "¡Reserva confirmada!";
    header("Location: ../views/mis_reservas.php");
  } else {
    $_SESSION['mensaje'] = "Error al reservar: " . mysqli_error($conn);
    header("Location: ../views/detalle.php?id=" . $hotel_id);
  }
}function obtenerReservasDelUsuario($usuario_id) {
  global $conn;
  $usuario_id = mysqli_real_escape_string($conn, $usuario_id);
  
  $query = "SELECT r.*, h.nombre AS hotel_nombre, h.ciudad 
            FROM reservas r 
            INNER JOIN hoteles h ON r.hotel_id = h.id
            WHERE r.usuario_id = '$usuario_id'
            ORDER BY r.fecha_entrada ASC";

  $result = mysqli_query($conn, $query);

  $reservas = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $reservas[] = $row;
  }
  return $reservas;
}