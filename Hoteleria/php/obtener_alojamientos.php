<?php
session_start();
include("../includes/conexion.php");

// Validar sesión
if (!isset($_SESSION['id_usuario'])) {
    die(json_encode(['error' => 'No autorizado']));
}

// Consulta para obtener alojamientos con información de tipo y estado
$sql = "SELECT a.*, t.nombre AS tipo_alojamiento, e.nombre AS estado 
        FROM alojamientos a
        JOIN tipos_alojamiento t ON a.id_tipo_alojamiento = t.id_tipo
        JOIN estados_alojamiento e ON a.id_estado = e.id_estado
        WHERE a.id_usuario = ?
        ORDER BY a.id_alojamiento DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['id_usuario']);
$stmt->execute();
$result = $stmt->get_result();

$alojamientos = [];
while ($row = $result->fetch_assoc()) {
    $alojamientos[] = $row;
}

$stmt->close();
$conn->close();

header('Content-Type: application/json');
echo json_encode($alojamientos);
?>