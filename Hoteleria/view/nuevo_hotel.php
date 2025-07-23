<?php
session_start();
include("../includes/header.php");
?>

<h2>Registrar nuevo hotel</h2>
<form method="POST" action="../controllers/PanelAnfitrionController.php" enctype="multipart/form-data">
  <label>Nombre del hotel:</label>
  <input type="text" name="nombre" required>

  <label>Ciudad:</label>
  <input type="text" name="ciudad" required>

  <label>Descripción:</label>
  <textarea name="descripcion" required></textarea>

  <label>Precio por noche:</label>
  <input type="number" name="precio" required>

  <label>Imagen principal:</label>
  <input type="file" name="imagen" accept="image/*" required>

  <button type="submit" name="registrar_hotel">Guardar hotel</button>
</form>

<?php include("../includes/footer.php"); ?>