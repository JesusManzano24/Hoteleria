<?php
// Mostrar errores en pantalla (solo para depuración)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Fuerza respuesta JSON y charset
header('Content-Type: application/json; charset=UTF-8');

// Incluye la conexión (ajusta la ruta si tu estructura es distinta)
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

// Validación de campos obligatorios
if (!$tipo || !$nombre || !$correo || !$password || !$telefono) {
    echo json_encode([
        'success' => false,
        'error'   => 'Faltan campos obligatorios'
    ]);
    exit;
}

// Mapear el string 'tipo' a la columna id_rol de tu tabla usuarios
switch ($tipo) {
    case 'Admin':
        $id_rol = 1;
        break;
    case 'anfitrion':
        $id_rol = 2;
        break;
    default:
    case 'Huesped':
        $id_rol = 3; // Asumiendo que 3 es el rol de Huesped
        break;
        echo json_encode([
            'success' => false,
            'error'   => 'Tipo de usuario inválido'
        ]);
        exit;
}

// Encriptar contraseña
$password_hash = hash('sha512', $password);

// ——— Verificar si el correo ya está registrado ———
$stmt = $conn->prepare('SELECT 1 FROM usuarios WHERE correo = ? LIMIT 1');
if (!$stmt) {
    echo json_encode([
        'success' => false,
        'error'   => 'SQL prepare falló (SELECT): ' . $conn->error
    ]);
    exit;
}
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

// ——— Insertar nuevo usuario ———
$stmt = $conn->prepare(
    'INSERT INTO usuarios
       (id_rol, nombre, correo, telefono, contraseña, genero, origen, fecha_nac, fecha_registro)
     VALUES (?,      ?,     ?,      ?,        ?,        ?,      ?,      ?,          ?)'
);
if (!$stmt) {
    echo json_encode([
        'success' => false,
        'error'   => 'SQL prepare falló (INSERT): ' . $conn->error
    ]);
    exit;
}
$stmt->bind_param(
    'issssssss',
    $id_rol,
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


