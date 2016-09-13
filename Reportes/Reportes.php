<?php
	require_once('../PDF/fpdf.php');
	public class Reportes
	{
		var $oPDF;
		public function __construct()
		{
			 //se obtienen los parametros del constructor
            $loParametros = func_get_args();
            //se obtiene el numero de parametros
            $liNumeroParametros = func_num_args();
            //se forma el nombre de el constructor con la palabra "__construct" y se añade
            //el numero de parametros
            $lsNombreConstructor = '__construct' . $liNumeroParametros;
            
            //Si el método existe, este se ejecuta
            if(method_exists($this, $lsNombreConstructor))
            {
                call_user_func_array(array($this, $lsNombreConstructor), $loParametros);
            }
		}

		private function constructorInterno($psOrientacion, $psUnidadMedida, $poSize, $psFamilia, $psEstilo, $piTamanio)
		{
			$this->oPDF = new FPDF($psOrientacion,$psUnidadMedida,$poSize);
			$this->oPDF->setFont()
		}

		private function __construct0()
		{
			$this->constructorInterno('P','mm', 'A4', 'Arial', 'I', 12);
		}

		private function __construct3($psOrientacion, $psUnidadMedida, $poSize)
		{
			$this->constructorInterno($psOrientacion, $psUnidadMedida, $poSize, 'Arial', 'I', 12);
		}
		private function __construct4($psOrientacion, $psUnidadMedida, $poSize, $psEstilo)
		{
			$this->constructorInterno($psOrientacion, $psUnidadMedida, $poSize, $psEstilo, 'I', 12);
		}
		private function __construct6($psOrientacion, $psUnidadMedida, $poSize, $psEstilo, $psEstilo, $piTamanio)
		{
			$this->constructorInterno($psOrientacion, $psUnidadMedida, $poSize, $psEstilo, $psEstilo, $piTamanio);
		}
	}
?>