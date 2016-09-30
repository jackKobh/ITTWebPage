<?php
	require_once('../BaseDeDatos/Conexion.php');
    require_once('../ManejoDatos/Sesion.php');
    require_once('../ManejoDatos/Sesion.php');
    require_once('../BaseDeDatos/IniciaConexion.php');

	$Usuario	= $_GET['Usuario'];
	$Password	= $_GET['Password'];

	$Salida = new StringBuilder();

	$psResult_Query = $poConexion->EjecutarEscalar('SELECT COUNT(USU_CORREO) FROM TAREAS.USUARIOS WHERE USU_CORREO="' . $Usuario . '" AND USU_CONTRASENA="' . $Password . '"');

	if($psResult_Query > 0)
	{
		$Salida->append('Access');
		$poSesion->set('poConexion', $poConexion);
	}
	else
	{
		$Salida->append('
		<div id="generic-modal" class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		  <div class="modal-dialog" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		        <h4 class="modal-title" id="myModalLabel">Error en loggin</h4>
		      </div>
		      <div class="modal-body">
		        Error en usuario o contraseña.
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		        <!--<button type="button" class="btn btn-primary">Save changes</button>-->
		      </div>
		    </div>
		  </div>
		</div>');
	}

	echo $Salida->toString();

?> 