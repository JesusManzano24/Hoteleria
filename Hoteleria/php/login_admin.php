<?php
session_start();
include("../includes/conexion.php");

header('Content-Type: application/json');

// Obtener los datos del formulario
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['correo']) || !isset($data['password'])) {
    echo json_encode(['error' => 'Faltan datos para el login']);
    exit;
}

$correo = $data['correo'];
$password = $data['password'];

// Consultar si el usuario existe y tiene rol de administrador
$sql = "SELECT * FROM usuarios WHERE correo = ? AND (id_rol = 1 OR id_rol = 2)"; // Ajusta los roles según corresponda
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $correo);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // El usuario existe, ahora verificar la contraseña
    $user = $result->fetch_assoc();
    if (password_verify($password, $user['password'])) {
        // La contraseña es correcta, iniciar sesión
        $_SESSION['admin_id'] = $user['id']; // Guardar en la sesión el ID del usuario
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Contraseña incorrecta']);
    }
} else {
    echo json_encode(['error' => 'Usuario no encontrado']);
}

$stmt->close();
$conn->close();
?>
