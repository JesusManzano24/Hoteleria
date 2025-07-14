<?php
include("../includes/db.php");

if (isset($_POST['login'])) {
  $email = $_POST['email'];
  $password = $_POST['password'];

  $query = "SELECT * FROM usuarios WHERE email='$email' AND password='$password'";
  $result = mysqli_query($conn, $query);

  if (mysqli_num_rows($result) == 1) {
    // Aquí va la lógica de sesión
    header("Location: ../index.php");
  } else {
    echo "Credenciales incorrectas";
  }
}