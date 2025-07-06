<?php session_start();
try {
    $error = '';
    $enviar = '';
    $enviado = '';

    //$conexion = new PDO('mysql:host=45.55.130.73;port=3306;dbname=restaurant','abdala','5Ctna31?');
    //$conexion = new PDO('mysql:host=localhost;port=3306;dbname=timfac','root','pekesona29');
    //bueno
    //$conexion = new PDO('mysql:host=localhost;port=3306;dbname=timfac_buena','factu_tim','F@ctu2020Tim');
    //localhost
    $conexion = new PDO('mysql:host=localhost;port=3306;dbname=ecomerce', 'root', 'elara29');
    //timfac-demo
    //$conexion = new PDO('mysql:host=localhost;port=3306;dbname=timfa-demo','factu-demo','F@ctu-d3m020');
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nombreusuario = $_POST['nombreusuario'];
        $password = $_POST['password'];
        $sql = $conexion->prepare('SELECT * FROM usuarios WHERE user = :nombreusuario AND 
     	                        pass = :pass And estatus = true;');
        $sql->execute(array(
            ':nombreusuario' => $nombreusuario,
            ':pass' => $password
        ));

        $resultado = $sql->fetch();

        if ($resultado != false) {
            /*try {
				$conexion = new PDO('mysql:host=localhost;port=3306;dbname=timfac','root','pekesona29');
				echo "Conectado\n";
			  } catch (Exception $e) {
				die("No se pudo conectar: " . $e->getMessage());
			  }*/

            if ($resultado['rol'] != "") {

                $_SESSION['userEcomer'] = $resultado['user'];
                $_SESSION['id_rol'] = $resultado['rol'];






                $enviar .=  '<center> Bienvenido <br>' . ucwords(utf8_encode($resultado['nombre'])) . '</center> <br>';
                $enviar .= '<meta http-equiv="refresh" content="4;url=../../index.php?action=index">';
                $enviado .= '<center><i class="fa fa-cog fa-spin fa-3x fa-fw"></i><br>
	                  <span class="">Accediendo Al Sistema...</span></center><br>';
            } else {
                session_destroy();
                $error .= '<li class="alert alert-danger"> El usuario no Existe </li>';
            }
        } else {
            session_destroy();
            $error .= '<li class="alert alert-danger"> Los Datos ingresados son Incorrectos </li>';
        }
    }
} catch (Exception $e) {
    session_destroy();
    echo "Error  de conexion ala base de datos.";
}









require 'Loguin.php';
