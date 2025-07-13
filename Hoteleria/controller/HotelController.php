<?php
include("../includes/db.php");

// Mostrar todos los hoteles
function mostrarHoteles() {
  global $conn;
  $query = "SELECT * FROM hoteles";
  $result = mysqli_query($conn, $query);

  while ($hotel = mysqli_fetch_assoc($result)) {
    echo "<div class='hotel'>";
    echo "<h3>" . $hotel['nombre'] . "</h3>";
    echo "<p>" . $hotel['descripcion'] . "</p>";
    echo "<a href='detalle.php?id=" . $hotel['id'] . "'>Ver más</a>";
    echo "</div>";
  }
}

// Obtener hotel por ID para detalle.php
function obtenerHotelPorId($id) {
  global $conn;
  $id = mysqli_real_escape_string($conn, $id);
  $query = "SELECT * FROM hoteles WHERE id='$id'";
  $result = mysqli_query($conn, $query);
  return mysqli_fetch_assoc($result);
}

// 🔍 Buscar hoteles por ciudad
function obtenerHotelesPorCiudad($ciudad) {
  global $conn;
  $ciudad = mysqli_real_escape_string($conn, $ciudad);
  $query = "SELECT * FROM hoteles WHERE ciudad LIKE '%$ciudad%'";
  return mysqli_query($conn, $query);
}

// 💸 Filtrar por rango de precio
function filtrarPorPrecio($min, $max) {
  global $conn;
  $query = "SELECT * FROM hoteles WHERE precio >= '$min' AND precio <= '$max'";
  return mysqli_query($conn, $query);
}

// 🧠 Buscar por palabra clave (nombre o descripción)
function buscarPorPalabraClave($keyword) {
  global $conn;
  $keyword = mysqli_real_escape_string($conn, $keyword);
  $query = "SELECT * FROM hoteles WHERE nombre LIKE '%$keyword%' OR descripcion LIKE '%$keyword%'";
  return mysqli_query($conn, $query);
}