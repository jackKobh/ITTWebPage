<?php
	//se importa clase para construir cadenas
	require_once  (__DIR__ . '/../ManejoDatos/StringBuilder.php');

	class ConexionSQLServer
	{
		//variables de clase 
		var $conexion;
		var $sb_errores;
		
		//Constructor
		function __construct($sqlHost, $sqlUser, $passwd, $name, $status = false)
		{
			//Se inicializa la variable para guardar los errores
			$this->sb_errores = new StringBuilder();
			//echo "Creacion SB";
			//se inicializa la conexion
                        $inf_conexion = array('Database'=>$name, 'UID' => $sqlUser, 'PWD' => $passwd);
			$this->conexion = sqlsrv_connect($sqlHost, $inf_conexion);
                        //sqlsrv_connect($name, $this->conexion);
			//echo "conexion realizada con exito";

			//si no se puede realizar la conexion, se muestra un mensaje de error,
			//siempre y cuando la bandera $status este en "true"
			if($this->conexion)
			{
				if($status)
				{

					echo 'Conectado a '.$sqlHost;
				}
			}
			else
			{
				if($status)
				{
					$this->sb_errores->appendFormat("Error numero {0}: Mensaje: {1}", array('ESC2004:__construct', $consulta));
					echo $this->sb_errores->toString();
					
				}
			}
		}

		function getConexion()
		{
			return $this->conexion;
		}
                
    

		function desconectar($status=false)
		{
			if(@mssql_close($this->conexion))
			{
				if($status)
				{
					echo 'Conexion cerrada';
				}
			}
			else
			{
				if($status)
				{
						echo 'Conexion no cerrada ';					
				}
			}
		}

		public function numeroRegistros($consulta)
		{
                    return mssql_num_rows(mssql_query($consulta));
		}

		public function ejecutarQuery($consulta, $status=false)
		{
			//$this->conexion->query("SET NAMES 'utf8'");
			if(@$respuesta = mssql_query($consulta))
			{
				if($status)
				{
					echo 'Ejecutado '.$consulta;
				}

				return $respuesta;
			}
			else
			{
				if($status)
				{
					$this->sb_errores->appendFormat("Error numero {0}: Mensaje: {1}", array('ESC2004:ejecutarQuery', $consulta));
					echo $this->sb_errores->toString();					
				}

				return 'ESC1002';
			}

		}

		public function ejecutarEscalar($consulta, $status = false)
		{
			if(@$respuesta = mssql_query($consulta))
			{
				if($status)
				{
					echo 'Ejecutado '.$consulta;
				}
				
				$row = mssql_fetch_row($respuesta);
				return $row[0];
			}
			else
			{
				if($status)
				{
					$this->sb_errores->appendFormat("Error numero {0}: Mensaje: {1}", array('ESC2004:ejecutaEscalar', $consulta));
					echo $this->sb_errores->toString();					
				}

				return 'ESC1003';;
			}
		}

		/*llave clase*/

	}
?>
