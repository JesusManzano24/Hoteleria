<?php
// 1) Suprimir cualquier warning/notice en salida
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

// Devolver siempre JSON
header('Content-Type: application/json; charset=UTF-8');
session_start();

// 2) Conexión
require __DIR__ . '/../includes/conexion.php';

// 3) Leer y validar payload
$payload = json_decode(file_get_contents('php://input'), true);
if (empty($payload['correo']) || empty($payload['password'])) {
    echo json_encode(['success' => false, 'error' => 'Faltan correo o contraseña']);
    exit;
}
$correo   = $payload['correo'];
$password = $payload['password'];

// 4) Preparar SELECT (alias obligatorio)
$sql = "
  SELECT 
    id_usuario,
    `contraseña` AS contrasena
  FROM usuarios
  WHERE correo = ? AND id_rol = 1
  LIMIT 1
";

$stmt = $conn->prepare($sql);
if (! $stmt) {
    echo json_encode(['success' => false, 'error' => 'Error en prepare: ' . $conn->error]);
    exit;
}

$stmt->bind_param('s', $correo);
if (! $stmt->execute()) {
    echo json_encode(['success' => false, 'error' => 'Error en execute: ' . $stmt->error]);
    exit;
}

$result = $stmt->get_result();
if (! $result || $result->num_rows !== 1) {
    echo json_encode(['success' => false, 'error' => 'Usuario no encontrado o no es admin']);
    exit;
}

$user = $result->fetch_assoc();
$stmt->close();

// 5) Asegurarnos de que el alias existe
if (! isset($user['contrasena'])) {
    echo json_encode(['success' => false, 'error' => 'No se encontró la contraseña en BD']);
    exit;
}

// 6) Rehashear la entrada y comparar
$hash_input = hash('sha512', $password);
if ($hash_input !== $user['contrasena']) {
    echo json_encode(['success' => false, 'error' => 'Contraseña incorrecta']);
    exit;
}

// 7) Login exitoso
$_SESSION['admin_id'] = $user['id_usuario'];
echo json_encode(['success' => true]);
exit;

