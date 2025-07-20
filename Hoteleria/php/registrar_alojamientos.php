<?php
session_start();
header('Content-Type: application/json');

include("../includes/conexion.php");

if (!isset($_SESSION['id_usuario'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Debes iniciar sesión.']);
    exit;
}

$campos = ['nombre', 'descripcion', 'id_tipo_alojamiento', 'id_estado', 'direccion', 'capacidad', 'precio', 'latitud', 'longitud'];

foreach ($campos as $campo) {
    if (empty($_POST[$campo])) {
        http_response_code(400);
        echo json_encode(['error' => "El campo $campo es requerido"]);
        exit;
    }
}

$id_usuario = $_SESSION['id_usuario'];
$nombre = trim($_POST['nombre']);
$descripcion = trim($_POST['descripcion']);
$id_tipo = intval($_POST['id_tipo_alojamiento']);
$id_estado = intval($_POST['id_estado']);
$direccion = trim($_POST['direccion']);
$capacidad = intval($_POST['capacidad']);
$precio = floatval($_POST['precio']);
$latitud = floatval($_POST['latitud']);
$longitud = floatval($_POST['longitud']);

$sql = "INSERT INTO alojamientos (id_usuario, nombre, id_tipo_alojamiento, direccion, descripcion, precio, capacidad, latitud, longitud, id_estado)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    http_response_code(500);
    echo json_encode(['error' => 'Error en la preparación: ' . $conn->error]);
    exit;
}

$stmt->bind_param("isisssddii", $id_usuario, $nombre, $id_tipo, $direccion, $descripcion, $precio, $capacidad, $latitud, $longitud, $id_estado);

if ($stmt->execute()) {
    echo json_encode(['success' => 'Alojamiento registrado correctamente']);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Error al registrar: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
