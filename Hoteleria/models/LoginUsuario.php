<?php
require_once __DIR__ . '/../ws/conexion.php';

class LoginUsuario
{
    /**
     * Intenta autenticar un usuario.
     * Retorna un array con:
     *  - success: bool
     *  - error (si success=false)
     *  - user_id y user_role (si success=true)
     */
    public static function autenticar(string $email, string $password): array
    {
        $conn = conectar();

        $sql = "
            SELECT id_usuario,
                   id_rol,
                   `contraseña` AS contrasena
            FROM usuarios
            WHERE correo = ?
            LIMIT 1
        ";
        $stmt = $conn->prepare($sql);
        if (! $stmt) {
            return ['success' => false, 'error' => 'Error interno en la consulta.'];
        }

        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if (! $result || $result->num_rows !== 1) {
            return ['success' => false, 'error' => 'Correo o contraseña inválidos.'];
        }

        $user = $result->fetch_assoc();
        $hashInput = hash('sha512', $password);
        if ($hashInput !== $user['contrasena']) {
            return ['success' => false, 'error' => 'Correo o contraseña inválidos.'];
        }

        return [
            'success'   => true,
            'user_id'   => (int)$user['id_usuario'],
            'user_role' => (int)$user['id_rol']
        ];
    }
}