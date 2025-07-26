<?php
require_once '/../models/RegistroUsuario.php';

class RegistroController
{
    public static function registrar()
    {
        // 1) Obtenemos todos los datos de form-data
        $data  = $_POST;
        $files = $_FILES;

        // 2) Depura solo si lo necesitas
        // var_dump($data, $files); exit;

        // 3) Llamamos al modelo, pasándole datos y archivos
        $resultado = RegistroUsuario::crear($data, $files);

        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($resultado);
    }
}