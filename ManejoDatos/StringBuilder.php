<?php
	class StringBuilder
	{
		private $_result;
                
                private $valor = NULL;

		public function __construct(){$this->_result = array();}

		public function __destruct(){$this->_ClearElements();}

		public function append($value){$this->_AddElement($value);}

		public function count(){return sizeof($this->_result);}

		public function appendFormat($string, $array_values){$this->_AddElementFormat($string, $array_values);}
                
                public function substring($start, $longitud = -1)
                {
                    $cadena = $this->toString();
                    //echo $cadena;             
                    if($longitud >= 0)
                    {
                        $cadena = substr($cadena, $start, $longitud);
                    }
                    else
                    {
                        $cadena = substr($cadena, $start);
                    }
                    
                    return $cadena;
                }
                
                public function length()
                {
                    return strlen($this->toString());
                }

                public function toString()
		{
			$retorna = "";
			foreach ($this->_result as $value) 
			{
				$retorna .= $value;
			}
                        $this->valor = $retorna;
			return $retorna;
		}

		//funciones privadas
		private function _AddElement($value,$pos=null)
		{
			if(!is_numeric($pos))
			{
				$this->_result[]=$value;
			}
			else
			{
				if(!$this->_IsValidIndex($pos)) $pos=$this->Count();
				array_splice($this->_result,$pos,0,$value);
			}
		}

		private function _AddElementFormat($string, $array_values)
		{
			$val_parametros = array();
			$tmp_reemplazo= "";
			$count = 0;
			try
			{
				foreach ($array_values as  $value) 
				{
					$val_parametros["{" . $count . "}"] = $value;
					$count++;
				}
				foreach ($val_parametros as $key => $value) 
				{
					$string = str_replace($key, $value, $string);	
				}
				$this->_result[] = $string;
			}
			catch(Exception $e)
			{
				echo "Error: " . $e->getMessage();
			}
		}

		private function _ClearElements(){unset($this->_result);}
	}


?>