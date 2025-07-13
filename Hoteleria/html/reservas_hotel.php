<?php
session_start();
include("../includes/header.php");
include("../controllers/PanelAnfitrionController.php");

if (isset($_GET['id'])) {
  $hotel_id = $_GET['id'];
  $reservas = obtenerReservasPorHotel($hotel_id, $_SESSION['usuario_id']);
}
?>

<h2>Reservas del hotel</h2>
<div class="reservas-hotel">
  <?php if (count($reservas) > 0): ?>
    <?php foreach ($reservas as $reserva): ?>
      <div class="reserva">
        <p><strong>Cliente:</strong> <?= $reserva['cliente'] ?></p>
        <p><strong>Entrada:</strong> <?= $reserva['fecha_entrada'] ?></p>
        <p><strong>Salida:</strong> <?= $reserva['fecha_salida'] ?></p>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <p>No hay reservas registradas para este hotel.</p>
  <?php endif; ?>
</div>

<?php include("../includes/footer.php"); ?>