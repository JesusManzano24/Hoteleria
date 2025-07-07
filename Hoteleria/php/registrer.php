<form method="POST" action="../controllers/AuthController.php">
  <h2>Registro de usuario</h2>
  <label>Nombre:</label>
  <input type="text" name="nombre" required>

  <label>Email:</label>
  <input type="email" name="email" required>

  <label>Contraseña:</label>
  <input type="password" name="password" required>

  <label>Rol:</label>
  <select name="rol">
    <option value="cliente">Cliente</option>
    <option value="anfitrion">Anfitrión</option>
  </select>

  <button type="submit" name="registro">Registrarse</button>
</form>