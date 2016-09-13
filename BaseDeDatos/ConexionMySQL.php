<?php
	//se importa clase para construir cadenas
	require_once  (__DIR__ . '/../ManejoDatos/StringBuilder.php');

	class ConexionMySQL
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
			$this->conexion = new mysqli($sqlHost, $sqlUser, $passwd, $name);
			//echo "conexion realizada con exito";

			//si no se puede realizar la conexion, se muestra un mensaje de error,
			//siempre y cuando la bandera $status este en "true"
			if(!$this->conexion->connect_errno)
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
					$this->sb_errores->appendFormat("Error numero {0}: Mensaje: {1}", array($this->conexion->connect_errno, $this->conexion->connect_error));
					echo $this->sb_errores->toString();
					
				}
			}
		}

		function getConexion()
		{
			return $this->conexion;
		}
                
                function getState()
                {
                    try
                    {
                        return $this->conexion->sqlstate;
                    } catch (Exception $ex) {
                        return 'ESC1001';
                    }
                    
                }

		function desconectar($status=false)
		{
			if(@$this->conexion->close())
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
			return @$this->conexion->query($consulta)->num_rows;
		}

		public function ejecutarQuery($consulta, $status=false)
		{
			$this->conexion->query("SET NAMES 'utf8'");
			if(@$respuesta = $this->conexion->query($consulta))
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
					$this->sb_errores->appendFormat("Error numero {0}: Mensaje: {1}", array($this->conexion->errno, $this->conexion->error));
					echo $this->sb_errores->toString();					
				}

				return 'ESC1002';
			}

		}

		public function ejecutarEscalar($consulta, $status = false)
		{
			if(@$respuesta = $this->conexion->query($consulta))
			{
				if($status)
				{
					echo 'Ejecutado '.$consulta;
				}
				
				$row = $respuesta->fetch_row();
				return $row[0];
			}
			else
			{
				if($status)
				{
					$this->sb_errores->appendFormat("Error numero {0}: Mensaje: {1}", array($this->conexion->errno, $this->conexion->error));
					echo $this->sb_errores->toString();					
				}

				return 'ESC1003';;
			}
		}

		/*llave clase*/

	}
?>
