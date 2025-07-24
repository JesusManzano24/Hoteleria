<?php
require_once __DIR__ . '../models/RegistroUsuario.php';

class RegistroController {
    public static function registrar() {
        $resultado = RegistroUsuario::crear($_POST);
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($resultado);
    }
}