<?php
	require_once (__DIR__ . '/../PDF/autoload.inc.php');
	use Dompdf\Dompdf;
	class ManejoReportes
	{
		var $PDF;
		var $html;
		var 
		function __construct()
		{
			$this->PDF = new Dompdf();
			$this->html = '';
		}



		public function Imprimir()
		{
			$this->PDF->render();
			$this->PDF->stream();
		}
	}

?>