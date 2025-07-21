<?php
session_start();
require __DIR__ . '/../includes/conexion.php';

// Solo aceptamos POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../html/login.html');
    exit;
}

// 1) Leer y validar
$email    = trim($_POST['email']    ?? '');
$password = trim($_POST['password'] ?? '');
if ($email === '' || $password === '') {
    $msg = 'Debes ingresar correo y contraseña.';
    header('Location: ../html/login.html?error=' . urlencode($msg));
    exit;
}

// 2) Traer usuario + rol + hash
$sql = "
  SELECT id_usuario, id_rol,
         `contraseña` AS contrasena
  FROM usuarios
  WHERE correo = ?
  LIMIT 1
";
$stmt = $conn->prepare($sql);
if (! $stmt) {
    $msg = 'Error interno en la consulta.';
    header('Location: ../html/login.html?error=' . urlencode($msg));
    exit;
}
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();

if (!$result || $result->num_rows !== 1) {
    $msg = 'Correo o contraseña inválidos.';
    header('Location: ../html/login.html?error=' . urlencode($msg));
    exit;
}

$user = $result->fetch_assoc();
$stmt->close();

// 3) Verificar contraseña
$hash_input = hash('sha512', $password);
if ($hash_input !== $user['contrasena']) {
    $msg = 'Correo o contraseña inválidos.';
    header('Location: ../html/login.html?error=' . urlencode($msg));
    exit;
}

// 4) Guardar sesión y redirigir según rol
$_SESSION['user_id']   = $user['id_usuario'];
$_SESSION['user_role'] = (int)$user['id_rol'];

switch ($_SESSION['user_role']) {
    case 2: // anfitrión
        header('Location: ../html/crud_alojamientos.html');
        break;
    case 3: // huésped
        header('Location: ../html/alojamientos.html');
        break;
    default:
        $msg = 'Rol no autorizado.';
        header('Location: ../html/login.html?error=' . urlencode($msg));
        break;
}

exit;
