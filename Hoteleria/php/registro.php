<?php include('includes/header.php'); ?>
<link rel="stylesheet" href="css/registro.css">

<h2>Registro de usuario</h2>
<form action="registro.php" method="POST">
  <label>Tipo de usuario:</label>
  <select name="tipo">
    <option value="cliente">Cliente</option>
    <option value="anfitrion">Anfitrión</option>
  </select>

  <label>Nombre:</label>
  <input type="text" name="nombre" required>

  <label>Correo:</label>
  <input type="email" name="correo" required>

  <label>Contraseña:</label>
  <input type="password" name="contrasena" required>

  <button type="submit">Registrarse</button>
</form>

<?php include('includes/footer.php'); ?>