<?php
session_start();
include("../includes/conexion.php");

header('Content-Type: application/json');

// Verificar que los datos necesarios estén presentes
if (
    !isset($_POST['tipo']) ||
    !isset($_POST['nombre']) ||
    !isset($_POST['correo']) ||
    !isset($_POST['password']) ||
    !isset($_POST['telefono']) ||
    !isset($_FILES['foto_perfil']) ||  // Asegúrate de validar si el archivo fue enviado
    !isset($_POST['genero']) ||
    !isset($_POST['origen']) ||
    !isset($_POST['fecha_nac'])
) {
    echo json_encode(['error' => 'Faltan datos para completar el registro']);
    exit;
}

$tipo_usuario = $_POST['tipo'];
$nombre = $_POST['nombre'];
$correo = $_POST['correo'];
$password = password_hash($_POST['password'], PASSWORD_BCRYPT);
$telefono = $_POST['telefono'];
$foto_perfil = $_FILES['foto_perfil']['name'];  // Foto de perfil
$genero = $_POST['genero'];
$origen = $_POST['origen'];
$fecha_nac = $_POST['fecha_nac'];
$fecha_registro = date('Y-m-d');  // Se maneja la fecha desde el servidor

// Guardar la foto de perfil en una carpeta
$target_dir = "../uploads/fotos_perfil/";
$target_file = $target_dir . basename($_FILES["foto_perfil"]["name"]);

// Verificar si la imagen se sube correctamente
if (move_uploaded_file($_FILES["foto_perfil"]["tmp_name"], $target_file)) {
    // Determinar el id_rol según el tipo de usuario
    $id_rol = ($tipo_usuario == 'cliente') ? 1 : 2;

    // Insertar los datos en la base de datos
    $sql = "INSERT INTO usuarios (id_rol, nombre, correo, password, telefono, foto_perfil, genero, origen, fecha_nac, fecha_registro)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("isssssssss", $id_rol, $nombre, $correo, $password, $telefono, $foto_perfil, $genero, $origen, $fecha_nac, $fecha_registro);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['error' => 'Error al registrar el usuario: ' . $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(['error' => 'Error en la preparación de la consulta']);
    }
} else {
    echo json_encode(['error' => 'Error al subir la foto de perfil']);
}

$conn->close();
?>
