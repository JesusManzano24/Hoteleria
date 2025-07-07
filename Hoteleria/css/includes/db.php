<?php
$conn = mysqli_connect("localhost", "root", "", "hotel_ecom");

if (!$conn) {
  die("Error de conexión: " . mysqli_connect_error());
}