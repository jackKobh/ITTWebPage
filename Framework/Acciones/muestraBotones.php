<?php
	require_once('../BaseDeDatos/Conexion.php');
    require_once('../ManejoDatos/Sesion.php');
    require_once('../ManejoDatos/StringBuilder.php');
    require_once('../BaseDeDatos/IniciaConexion.php');

	function regresaBotones($Conexion,$padre = NULL, $contador)
	{
		//variables de manejo de cadenas
		$salida = new StringBuilder();
		$query = new StringBuilder();

		//se crea consulta para verificar los menus que son principales
		//estos se identifican por que le campo NC_ID_PADRE es NULL
		$query->append('SELECT * FROM NAV_CONF WHERE');
		if(is_null($padre))
		{
			$query->append(' NC_ID_PADRE IS NULL');
		}
		else
		{
			$query->appendFormat(' NC_ID_PADRE={0}',[$padre]);
		}
		//Se añade la cabecera de la lista para los botones
		$salida->append('<ul class="nav navbar-nav">');

		//Se recorren los resultados de los botones extridos en este nivel
		foreach($Conexion->ejecutarQuery($query->toString()) as $data) 
		{
			$query = new StringBuilder();
			$query->appendFormat('SELECT COUNT(*) FROM NAV_CONF WHERE NC_ID_PADRE={0}',[$data['NC_ID_ELEMENTO']]);
			//echo $query->toString();
			$noHijos = $Conexion->ejecutarEscalar($query->toString());

			if($noHijos > 0)
			{
				$salida->appendFormat('
					<li class="dropdown">
						<a href="" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" role="button">
							{1} 
							<span class="caret"></span>
						</a>
						<ul class="dropdown-menu">
							{0}
						</ul>
					</li>
					',[regresaBotones($Conexion,$data['NC_ID_ELEMENTO'], $contador + 1)
					 , $data['NC_DESCRIPCION']
					 , $data['NC_HREF']]);
				//echo $salida->toString();

			}
			else
			{
				$salida->appendFormat('<li><a href="{0}">{1}</a></li>',[$data['NC_HREF'], $data['NC_DESCRIPCION']]);
			}
		}

		$salida->append('</ul>');

		return $salida->toString().$contador;

	}

	echo regresaBotones($poConexion,NULL,1);
?>