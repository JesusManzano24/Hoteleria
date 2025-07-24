<?php
require_once __DIR__ . '/../controllers/RegistroController.php';

class RegistroUsuario {
    public static function crear($datos) {
        $conn = conectar(); // asumes que esta función retorna $conn

        // Mapeo seguro
        $tipo           = $datos['tipo']           ?? '';
        $nombre         = $datos['nombre']         ?? '';
        $correo         = $datos['correo']         ?? '';
        $password       = $datos['password']       ?? '';
        $telefono       = $datos['telefono']       ?? '';
        $genero         = $datos['genero']         ?? '';
        $origen         = $datos['origen']         ?? '';
        $fecha_nac      = $datos['fecha_nac']      ?? '';
        $fecha_registro = $datos['fecha_registro'] ?? '';

        // Validación básica
        if (!$tipo || !$nombre || !$correo || !$password || !$telefono) {
            return ['success' => false, 'error' => 'Faltan campos obligatorios'];
        }

        switch ($tipo) {
            case 'Admin':     $id_rol = 1; break;
            case 'anfitrion': $id_rol = 2; break;
            case 'Huesped':   $id_rol = 3; break;
            default: return ['success' => false, 'error' => 'Tipo de usuario inválido'];
        }

        $password_hash = hash('sha512', $password);

        // Verificar si ya existe el correo
        $stmt = $conn->prepare('SELECT 1 FROM usuarios WHERE correo = ? LIMIT 1');
        if (!$stmt) return ['success' => false, 'error' => 'SQL prepare falló: ' . $conn->error];
        $stmt->bind_param('s', $correo);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->close();
            return ['success' => false, 'error' => 'Este correo ya está registrado'];
        }
        $stmt->close();

        // Insertar nuevo usuario
        $stmt = $conn->prepare('INSERT INTO usuarios (id_rol, nombre, correo, telefono, contraseña, genero, origen, fecha_nac, fecha_registro) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
        if (!$stmt) return ['success' => false, 'error' => 'SQL prepare falló (INSERT): ' . $conn->error];

        $stmt->bind_param('issssssss', $id_rol, $nombre, $correo, $telefono, $password_hash, $genero, $origen, $fecha_nac, $fecha_registro);

        if (!$stmt->execute()) {
            $error = $stmt->error;
            $stmt->close();
            return ['success' => false, 'error' => 'Error al registrar: ' . $error];
        }

        $stmt->close();
        return ['success' => true];
    }
}
