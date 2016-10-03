<?php
	require_once(__DIR__ . '/../ManejoDatos/Sesion.php');

/* 
 * Clase para inicializar la conexion de manera automatica sin necesidad de 
 * crear la conexion manualmente en cada clase, esta clase se piensa generar de
 * manera automatica en el instalador a crear.
 */
	$poSesion = new Sesion();
	$poConexion = NULL;
	if($poSesion->get('poConexion') == false)
	{
		$poConexion = new Conexion('192.168.12.15','root', 'urbano123', 'ITTWEBPAGE', 'MySQL');
	}
	else
	{
		$poConexion = $poSesion->get('poConexion');
	}
	//if $poSesion->get('poConexion');
	//echo $Conexion;
     //($Servidor, $Usuario, $Clave, $NombreBD, $MotorBD);
    


?>

