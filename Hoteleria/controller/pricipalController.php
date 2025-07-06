<?php
ob_start();

class MvcController
{

	public function plantilla()
	{

		include 'view/Principal/Principal.php';
	}

	#INTERACCIÓN DEL USUARIO
	#----------------------------------------------
	public function enlacesPaginasController()
	{

		if (isset($_GET["action"])) {

			$enlacesController = $_GET["action"];
		} else {

			$enlacesController = "index";
		}
		// le pide al modelo y que conecte con :: al método y asi heredo la clase y sus metodos y atributos..

		$respuesta = Paginas::enlacesPaginasModel($enlacesController);
		require $respuesta;
	}





	//=============================================================================
	//FIN DE RESERVAS
	//============================================================================
	public function getDashController()
	{

		date_default_timezone_set('America/Monterrey');
		$hoy = date('Y-m-d');
		$respuesta = Datos::getDashModel('pirncipal_dashboard', $_SESSION['id_user']);
		# code...
		return $respuesta;
	}
	public function getFolController()
	{

		date_default_timezone_set('America/Monterrey');

		$respuesta = Datos::getFolModel('folios_timbres', $_SESSION['id_user']);
		# code...
		return $respuesta;
	}
	public function getFolController2()
	{

		date_default_timezone_set('America/Monterrey');

		$respuesta = Datos::getFolModel('folios_timbres_timbrado', $_SESSION['id_user']);
		# code...
		return $respuesta;
	}
	public static function getEmitidasController()
	{
		$respuesta = null;
		date_default_timezone_set('America/Monterrey');
		if ($_SESSION['id_rol'] == "3")
			$respuesta = Datos::getEmitidasModel('cfdixml_33_timbrado', $_SESSION['id_user']);
		else
			$respuesta = Datos::getEmitidasModel('cfdixml_33', $_SESSION['id_user']);
		$retorna = null;



		foreach ($respuesta as $row) {

			if ($row['cfdi_tipo'] == "I")
				$tipo = "I-INGRESO";
			if ($row['cfdi_tipo'] == "E")
				$tipo = "E-EGRESO";
			if ($row['cfdi_tipo'] == "T")
				$tipo = "T-TRASLADO";
			if ($row['cfdi_tipo'] == "N")
				$tipo = "N-NOMINA";
			if ($row['cfdi_tipo'] == "P")
				$tipo = "P-PAGO";
			if ($_SESSION['id_rol'] == "3") {
				$row['cfdi_fecha_creacion'] = $row['cfdi_fecha'];
				$row['cfdi_status'] = $row['cfdi_stauts'];
			}
			$retorna = '<tr> 
		    	<td align="center">
				  <a class="fa fa-eye  btn btn-warning  btn-sm" style="color:black;" href="index.php?action=vercfdi&&idcfdi=' . $row['id_cfdi'] . '" title="VER CFDI" ></a>
				 </td>
				 <td align="center" style="font-size:12px;">' . $row['cfdi_foliofis'] . '</td>
				 <td align="center" style="font-size:12px;"> ' . $row['cfdi_fecha_creacion'] . '</td>
				 <td align="center" style="font-size:12px;"> ' . $row['cfdi_serie'] . '-' . $row['cfdi_folio'] . '</td>
				 <td align="center" style="font-size:12px;"> ' . $row['cfdi_receptor_rfc'] . '-' . $row['cfdi_receptor_razon'] . '</td>
				 <td align="center" style="font-size:12px;"> ' . $row['total'] . '</td>
				 <td align="center"> ' . $tipo . '</td>';
			$can = 0;
			if ($row['cfdi_status'] == "Timbrado" || $row['cfdi_status'] == "Timbrado y Pagado")
				$retorna .= '<td align="center" style="color:green;font-size:12px;"> ' . $row['cfdi_status'] . '</td>';
			else {
				$estatus = explode("-", $row['cfdi_status']);
				switch ((int)$estatus[0]) {
					case 101:
						$retorna .= '<td align="center" style="color:orange,font-size:11px;"> ' . $estatus[1] . '</td>';
						$can = 1;
						break;
					case 102:
						$retorna .= '<td align="center" style="color:orange,font-size:11px;"> ' . $estatus[1] . '</td>';
						$can = 1;
						break;
					case 103:
						$retorna .= '<td align="center" style="color:red,font-size:11px;"> ' . $estatus[1] . '</td>';
						$can = 1;
						break;
					case 104:
						$retorna .= '<td align="center" style="color:orange;font-size:11px;"> ' . $estatus[1] . '</td>';
						$can = 1;
						break;
					case 105:
						$retorna .= '<td align="center" style="color:orange;font-size:11px;"> ' . $estatus[1] . '</td>';
						$can = 1;
						break;
					case 106:
						$retorna .= '<td align="center" style="color:orange;font-size:11px;"> ' . $estatus[1] . '</td>';
						$can = 1;
						break;
					case 107:
						$retorna .= '<td align="center" style="color:red;font-size:11px;"> ' . $estatus[1] . '</td>';
						$can = 1;
						break;
					case 201:
						$retorna .= '<td align="center" style="color:red;font-size:11px;"> ' . $estatus[1] . '</td>';
						$can = 1;
						break;
					case 202:
						$retorna .= '<td align="center" style="color:red;font-size:11px;"> ' . $estatus[1] . '</td>';
						$can = 1;
						break;
					case 203:
						$retorna .= '<td align="center" style="color:orange;font-size:11px;"> ' . $estatus[1] . '</td>';
						$can = 1;
						break;
					case 205:
						$retorna .= '<td align="center" style="color:orange;font-size:11px;"> ' . $estatus[1] . '</td>';
						$can = 1;
						break;
					default:
						$retorna .= '<td align="center" style="color:orange;font-size:11px;"> ' . $estatus[1] . '</td>';
				}
			}
			$retorna .= '<td align="center">
				 <a class="fa fa-ban  btn btn-danger btn-sm" style="color:black;" href="index.php?action=cancelar&idCancelar=' . $row['id_cfdi'] . '" title="CANCELAR CFDI"> </a>&nbsp;&nbsp;&nbsp;';


			if ($_SESSION['id_rol'] != "3") {
				if ($row['cfdi_formapago'] == 'PPD' && $row['cfdi_status'] != "Timbrado y Pagado" && $can != 1)
					$retorna .= '<a class="fa  btn btn-primary btn-sm" style="color:white;" href="index.php?action=pago&IdPago=' . $row['id_cfdi'] . '" title="REALIZAR COMPLEMENTO DE PAGO">PAGO </a>';
				if ($row['cfdi_tipo'] == "I")
					$retorna .= '<a class="fa fa-copy fa-2x  btn btn-sm " style="color:black;" href="index.php?action=clona&IdClonar=' . $row['id_cfdi'] . '" title="COPIAR CFDI"> </a>';
			}
			$retorna .= '</td></tr>';
		}
		return $retorna;
	}

	public static function transformaXmlController($id)
	{
		$datos = Datos::transformaXmlModel($id);

		return $datos;
	}
	public static function transformaXmlTimbradoController($xml)
	{
		$datos = Datos::transformaXmlTimbradoModel($xml);

		return $datos;
	}
	public static function getQrController($text, $nombrearch)
	{
		$datosController = array("texto" => $text, "nombre" => $nombrearch);
		$datos = Datos::getQrModel($datosController);
		return $datos;
	}
	public function DescargarCFDI($uuid, $rfc, $iduser, $seriefolio)
	{
		if (isset($_POST['descargaCFDI'])) {

			$datosController = array(
				"uuid" => $uuid,
				"rfc" => $rfc,
				"seriefolio" => $seriefolio,
				"iduser" => $iduser
			);
			if ($_SESSION["id_rol"] == "3")
				$tabla = "cfdixml_33_timbrado";
			else
				$tabla = "cfdixml_33";

			$respuesta = Datos::descargaCFDIModel($datosController, $tabla);


			if ($respuesta != 'success') {
				echo $respuesta;
			} else {
				echo $respuesta;
			}
		}
	}
	public function retornaXMLController($uuid)
	{
		if ($_SESSION["id_rol"] == "3")
			$tabla = "cfdixml_33_timbrado";
		else
			$tabla = "cfdixml_33";
		$respuesta = Datos::retornaXMLModel($uuid, $tabla);
		return $respuesta;
	}
	public function retornaacuseXMLController($uuid)
	{

		$respuesta = Datos::retornaacuseXMLModel($uuid);
		return $respuesta;
	}
	public static function devuelveXMLController($id)
	{

		if ($_SESSION["id_rol"] == "3")
			$tabla = "cfdixml_33_timbrado";
		else
			$tabla = "cfdixml_33";
		$respuesta = Datos::devuelveXMLModel($id, $tabla);
		return $respuesta;
	}
	public static function makePDFController($datosController)
	{
		$respuesta = Datos::makePDF($datosController);
		return $respuesta;
	}
}
