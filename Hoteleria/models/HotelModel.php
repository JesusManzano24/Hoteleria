<?php
require_once("../includes/db.php");

class HotelModel {

  public static function obtenerTodos() {
    global $conn;
    $query = "SELECT * FROM hoteles";
    return mysqli_query($conn, $query);
  }

  public static function obtenerPorId($id) {
    global $conn;
    $id = mysqli_real_escape_string($conn, $id);
    $query = "SELECT * FROM hoteles WHERE id='$id'";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result);
  }

  public static function obtenerPorAnfitrion($anfitrion_id) {
    global $conn;
    $anfitrion_id = mysqli_real_escape_string($conn, $anfitrion_id);
    $query = "SELECT * FROM hoteles WHERE creador_id='$anfitrion_id'";
    return mysqli_query($conn, $query);
  }

  public static function crearHotel($datos) {
    global $conn;
    extract($datos); // nombre, ciudad, descripcion, precio, imagen, creador_id
    $query = "INSERT INTO hoteles (nombre, ciudad, descripcion, precio, imagen, creador_id)
              VALUES ('$nombre', '$ciudad', '$descripcion', '$precio', '$imagen', '$creador_id')";
    return mysqli_query($conn, $query);
  }
}