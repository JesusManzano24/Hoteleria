<?php
// Mostrar errores en pantalla (solo para depuración)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Fuerza respuesta JSON y charset
header('Content-Type: application/json; charset=UTF-8');

// Incluye la conexión (ajusta la ruta si está en otro directorio)
require __DIR__ . '/../includes/conexion.php';

// Verifica la conexión ($conn proviene de conexion.php)
if ($conn->connect_error) {
    echo json_encode([
        'success' => false,
        'error'   => 'Error de conexión: ' . $conn->connect_error
    ]);
    exit;
}

// Mapeo de campos (deben coincidir con los name= de tu formulario)
$tipo           = $_POST['tipo']           ?? '';
$nombre         = $_POST['nombre']         ?? '';
$correo         = $_POST['correo']         ?? '';
$password       = $_POST['password']       ?? '';
$telefono       = $_POST['telefono']       ?? '';
$genero         = $_POST['genero']         ?? '';
$origen         = $_POST['origen']         ?? '';
$fecha_nac      = $_POST['fecha_nac']      ?? '';
$fecha_registro = $_POST['fecha_registro'] ?? '';

// Validación básica
if (!$tipo || !$nombre || !$correo || !$password || !$telefono) {
    echo json_encode([
        'success' => false,
        'error'   => 'Faltan campos obligatorios'
    ]);
    exit;
}

// Encriptar contraseña
$password_hash = hash('sha512', $password);

// Verificar si el correo ya está registrado
$stmt = $conn->prepare('SELECT id FROM usuarios WHERE correo = ?');
$stmt->bind_param('s', $correo);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode([
        'success' => false,
        'error'   => 'Este correo ya está registrado'
    ]);
    $stmt->close();
    exit;
}
$stmt->close();

// Insertar nuevo usuario
$stmt = $conn->prepare(
    'INSERT INTO usuarios
     (tipo, nombre, correo, telefono, contrasena, genero, origen, fecha_nac, fecha_registro)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)'
);
$stmt->bind_param(
    'sssssssss',
    $tipo,
    $nombre,
    $correo,
    $telefono,
    $password_hash,
    $genero,
    $origen,
    $fecha_nac,
    $fecha_registro
);

if (!$stmt->execute()) {
    echo json_encode([
        'success' => false,
        'error'   => 'Error al registrar: ' . $stmt->error
    ]);
    $stmt->close();
    exit;
}

$stmt->close();

// Respuesta de éxito
echo json_encode(['success' => true]);
exit;


