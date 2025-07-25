<?php
require_once __DIR__ . '/../ws/conexion.php';

class Admin
{
    /**
     * Intenta autenticar un admin por correo y contraseña.
     * Retorna array:
     *  - success: bool
     *  - error (si success=false)
     *  - admin_id (si success=true)
     */
    public static function authenticate(string $correo, string $password): array
    {
        $conn = conectar();

        $sql = "
            SELECT 
                id_usuario,
                `contraseña` AS contrasena,
                id_rol
            FROM usuarios
            WHERE correo = ?
              AND id_rol = 1
            LIMIT 1
        ";
        $stmt = $conn->prepare($sql);
        if (! $stmt) {
            return ['success' => false, 'error' => 'Error interno en la consulta.'];
        }

        $stmt->bind_param('s', $correo);
        if (! $stmt->execute()) {
            return ['success' => false, 'error' => 'Error en ejecución: ' . $stmt->error];
        }

        $result = $stmt->get_result();
        $stmt->close();

        if (! $result || $result->num_rows !== 1) {
            return ['success' => false, 'error' => 'Administrador no encontrado.'];
        }

        $user      = $result->fetch_assoc();
        $hashInput = hash('sha512', $password);

        if ($hashInput !== $user['contrasena']) {
            return ['success' => false, 'error' => 'Contraseña incorrecta.'];
        }

        return ['success' => true, 'admin_id' => (int)$user['id_usuario']];
    }
}