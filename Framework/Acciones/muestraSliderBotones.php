<?php
	require_once('../BaseDeDatos/Conexion.php');
    require_once('../ManejoDatos/Sesion.php');
    require_once('../ManejoDatos/StringBuilder.php');
    require_once('../BaseDeDatos/IniciaConexion.php');

    //$imagenes = new StringBuilder();
    $botones = new StringBuilder();
    $salida = new StringBuilder();

    $contador = 0;

    $query = new StringBuilder();

    $query->append('SELECT * FROM SLIDER_CONF WHERE SC_STATUS = 1');



    foreach($poConexion->ejecutarQuery($query->toString()) as $data) 
    {
    /*
    	$inicial = '';
    	if($contador == 0)
    	{
    		$inicial = 'active';
    	}
    	$imagenes->appendFormat('
    			<div class="item {0}">
					<a href="{1}">
						<img src="{2}" alt="Chania">
					</a>
				</div>
    		',[$inicial,$data['SC_HREF'], $data['SC_NOMBRE']]);*/

    	$inicial = '';
    	if($contador == 0)
    	{
    		$inicial = 'class="active"';
    	}

    	$botones->appendFormat('
				<li data-target="#slider-principal" data-slide-to="{0}" {1}></li>
    		',[$contador,$inicial]);
    	$contador++;
    }

    echo $botones->toString();
?>