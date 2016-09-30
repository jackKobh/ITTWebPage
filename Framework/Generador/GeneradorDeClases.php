<?php

/* 
 * Esta clase permite generar objetos PHP para el manejo de datos de la base de datos
 * las cuales se realizarán de manera automatica
 */
    require_once __DIR__ . '/../BaseDeDatos/Conexion.php';
    require_once __DIR__ . '/../ManejoDatos/Sesion.php';
    require_once __DIR__ . '/../ManejoDatos/StringBuilder.php';
    class Generador
    {
        var $csRutaSalida;
        var $csFiltro;
        var $coConexion;
        var $coSesion;
        var $csSalto;
        public function __construct() 
        {
            $this->coSesion= new Sesion();
            $this->coConexion = $this->coSesion->get('Conexion');
            $loParametros = func_get_args();
            //se obtiene el numero de parametros
            $liNumeroParametros = func_num_args();
            //se forma el nombre de el constructor con la palabra "__construct" y se añade
            //el numero de parametros
            $lsNombreConstructor = '__construct' . $liNumeroParametros;
            $this->csSalto = chr(10) . chr(13);
            
            //Si el método existe, este se ejecuta
            if(method_exists($this, $lsNombreConstructor))
            {
                call_user_func_array(array($this, $lsNombreConstructor), $loParametros);
            }
        }
        
        function __construct0()
        {
            $this->__inicializaInformacion(__DIR__ . '/../ObjetosDeDatos', '');
        }
        
        function __construct1($psRutaSalida)
        {
            $this->__inicializaInformacion($psRutaSalida, '');
        }
        
        function __construct2($psRutaSalida, $psFiltro)
        {
            $this->__inicializaInformacion($psRutaSalida, $psFiltro);
        }
        
        function __inicializaInformacion($psRutaSalida, $psFiltro)
        {
            $this->csFiltro = $psFiltro;
            $this->csRutaSalida = $psRutaSalida;
            
            $this->CreaDirectorioSalida();
            $this->GeneraClasesBase();
            //echo $psRutaSalida . '</br>';
        }
        
        function CreaDirectorioSalida()
        {
            if(!file_exists($this->csRutaSalida))
            {
                mkdir($this->csRutaSalida, 0775, true);
            }
        }
        
        function GeneraClasesBase()
        {
            /*
             * Esta funcion tiene como objetivo el realizar la consulta de las tablas existentes en la BD
             * para poder crear un archivo .php para cada una de ellas. Esto permitirá realizar el manejo
             * de los datos de la base de datos de una manera sencilla ya que las operaciones se realizarán
             * a nivel de objetos
             */
            
            //recorrido de las tablas de la BD
            foreach($this->coConexion->ejecutarQuery('SHOW TABLES') as $data)
            {
                //funcion para crear los archivos .php por tabla
                //echo $data['Tables_in_' . strtolower($this->coConexion->csNombreBD)];
                $this->CreaArchivoFuente($data['Tables_in_' . strtolower($this->coConexion->csNombreBD)]);
            }
        }
        
        function CreaArchivoFuente($psNombreTabla)
        {
            
            // se obtiene el nombre del archivo a crear
            $lsNombreArchivo = $this->csRutaSalida . '/' . $psNombreTabla . '.php';
            //echo $lsNombreArchivo . "</br>";
            //si el archivo no existe se crea
            //If (!file_exists($lsNombreArchivo))
            //{
                //si el archivo se abre correctamente entra a la condicion
                //echo $lsNombreArchivo;
            if($loArchivo = fopen($lsNombreArchivo, 'w'))
            {
                //se comienza a escribir el contenido de los archivos .php de acuerdo a las propiedades
                //de la tabla de la BD

                //Variable para almacenar el código fuente del archivo .php
                //echo "Entre a la creacion de archivo " . $lsNombreArchivo . " </br>";;
                $lsCodigoFuente = new StringBuilder();
                /*
                 * Se comienza por crear el encabezado de los archivos los cuales contendrán las importaciones
                 * y el nombre de la clase
                 */
                $lsCodigoFuente->append($this->GeneraEncabezado($psNombreTabla));
                //echo $lsCodigoFuente->toString();

                /*
                 * Se comienza por declarar las propiedades de la clase
                 */
                $lsCodigoFuente->append($this->GeneraPopiedades($psNombreTabla));
                
                /*
                 * Se generan los constructores de clase.
                 */
                $lsCodigoFuente->append($this->GeneraConstructor());

                
                /*
                 * Se generan las funciones del CRUD.
                 */
                $lsCodigoFuente->append($this->GeneraFuncionesCRUD());
                
                /*
                 * Se escribe en el archivo el codigo generado.
                 */
                fwrite($loArchivo, $lsCodigoFuente->toString());
            }
            //}
        }
        /*
         * Se crea la funcion para crear el constructor de las clases de objetos de base de datos
         */
        
        function GeneraEncabezado($psNombreTabla)
        {
            $lsCodigoFuente = new StringBuilder();
            $lsCodigoFuente->append('<?php' . $this->csSalto);
            $lsCodigoFuente->append('    require_once __DIR__ . \'/../BaseDeDatos/Conexion.php\';' . $this->csSalto);
            $lsCodigoFuente->append('    require_once __DIR__ . \'/../ManejoDatos/Sesion.php\';' . $this->csSalto);
            $lsCodigoFuente->appendFormat('    class {0}' . $this->csSalto, array($psNombreTabla));
            $lsCodigoFuente->append('    {' . $this->csSalto);
            
            
            return $lsCodigoFuente->toString();
        }
        
       
        
        function GeneraPopiedades($psNombreTabla)
        {
            $lsCodigoFuente = new StringBuilder();
            $lsCodigoPK = new StringBuilder();
            $lsCampos = New StringBuilder();
            $lsVariables = new StringBuilder();
            
            /*
             * Se declaran las variables generales para las clases genericas como son
             * la sesion y la conexion a base de datos
             */
            
            $lsVariables->append('      var $coSesion;' . $this->csSalto);
            $lsVariables->append('      var $coConexion;' . $this->csSalto);
            $lsVariables->appendFormat('       var $NOMBRETABLA = "{0}";' . $this->csSalto, [$psNombreTabla]);
            $lsVariables->append('      var $coDatos;' . $this->csSalto);
            
            /*
             * Se obtiene la estructura de la tabla para crear las propiedades.
             */
            $lsQuery = $this->ConsultaEstructuraTabla($psNombreTabla);
            //echo $lsQuery . "</br>";
            
            /*
             * Se crea el encabezado para crear las variables que contienen las llaves
             * primarias y el arreglo con los campos.
             */
            $lsCodigoPK->append('       public $LLAVESPK = [');
            $lsCampos->append('     public $CAMPOS = [');
            
            /*
             * Se inicia el ciclo para recorrer todos los campos y llenar las propiedades 
             * y llenar los arreglos de llaves primarias y campos.
             */
            foreach($this->coConexion->ejecutarQuery($lsQuery) as $data)
            {
                /*
                 * Se crea las propiedades 
                 */
                
                $lsCodigoFuente->appendFormat('         public ${0} = ["{1}" => "{0}",' . $this->csSalto, array($data['COLUMN_NAME'],'Nombre'));
                $lsCodigoFuente->appendFormat('             "{0}" => "{1}",' . $this->csSalto, array('Tipo', $data['DATA_TYPE']));        
                $lsCodigoFuente->appendFormat('             "{0}" => "{1}",' . $this->csSalto, array('Longitud', $data['CHARACTER_MAXIMUM_LENGTH']));
                $lsCodigoFuente->appendFormat('             "{0}" => "{1}",' . $this->csSalto, array('Nulo', $data['IS_NULLABLE']));
                $lsCodigoFuente->appendFormat('             "{0}" => "{1}",' . $this->csSalto, array('Llave', $data['COLUMN_KEY']));
                $lsCodigoFuente->appendFormat('             "{0}" => "{1}",' . $this->csSalto, array('Default', $data['COLUMN_DEFAULT']));
                $lsCodigoFuente->appendFormat('             "{0}" => "{1}",' . $this->csSalto, array('Extra', $data['EXTRA']));
                $lsCodigoFuente->appendFormat('             "{0}" => "{1}",' . $this->csSalto, array('TablaRef', $data['REFERENCED_TABLE_NAME']));
                $lsCodigoFuente->appendFormat('             "{0}" => "{1}",' . $this->csSalto, array('Valor', ''));
                $lsCodigoFuente->appendFormat('             "{0}" => "{1}"];' . $this->csSalto, array('ColumRef', $data['REFERENCED_COLUMN_NAME']));
                
                /*
                 * Si el campo es una llave primaria se almacena en el arreglo de llaves
                 */
                if($data['COLUMN_KEY'] == 'PRI')
                {
                    $lsCodigoPK->appendFormat('"{0}",',[$data['COLUMN_NAME']]);
                }
                
                /*
                 * Se guardan los nombres de las columnas en un arreglo.
                 */
                $lsCampos->appendFormat('"{0}",',[$data['COLUMN_NAME']]);
            }
            
            /*
             * Se guarda en la variable de salida las variables
             */
            $lsCodigoFuente->append($this->csSalto . $lsVariables->toString());
            
            /*
             * Se guarda en la variable de salida el arreglo de campos.
             */
            $lsCodigoFuente->append($this->csSalto . $lsCampos->substring(0,$lsCampos->length() - 1) . '];');
            
            /*
             * Se guarda en la variable de salida en el arreglo de llaves.
             */
            $lsCodigoFuente->append($this->csSalto . $lsCodigoPK->substring(0,$lsCodigoPK->length() - 1) . '];');
            //echo $lsCodigoPK->substring(0,$lsCodigoPK->length() - 1) . '];';
            
            $lsCodigoFuente->append($this->GeneraPropiedadesVariablesPublicas());
            return $lsCodigoFuente->toString();
        }
        
        private function GeneraPropiedadesVariablesPublicas()
        {
            $lsCodigoFuente = new StringBuilder();
            $lsCodigoFuente->append('       ' . $this->csSalto);
            return $lsCodigoFuente->toString();
        }
        /*
         * Funcion para crear los constructores, por el momento solamente se crean dos, uno
         * donde se obtiene la conexion de la variable de sesion y la otra en donde se obtiene de un parametro
         */
        function GeneraConstructor()
        {
            $lsCodigoFuente = new StringBuilder();
               /*
                * Se crea la funcion general de el constructor en la que se seleccionara que constructor se ejecutará de acuerdo
                * a el numero de parametros que se ingresen
                */
            
            $lsCodigoFuente->append('       function __construct()' . $this->csSalto);
            $lsCodigoFuente->append('       {' . $this->csSalto);
            $lsCodigoFuente->append('           $this->coSesion= new Sesion();' . $this->csSalto);
            $lsCodigoFuente->append('           $loParametros = func_get_args();' . $this->csSalto);
            $lsCodigoFuente->append('           $liNumeroParametros = func_num_args();' . $this->csSalto);
            $lsCodigoFuente->append('           $lsNombreConstructor = "__construct" . $liNumeroParametros;' . $this->csSalto);
            $lsCodigoFuente->append('           if(method_exists($this, $lsNombreConstructor))' . $this->csSalto);
            $lsCodigoFuente->append('           {' . $this->csSalto);
            $lsCodigoFuente->append('               call_user_func_array(array($this, $lsNombreConstructor), $loParametros);' . $this->csSalto);
            $lsCodigoFuente->append('           }' . $this->csSalto);
            $lsCodigoFuente->append('        }' . $this->csSalto);
            
            /*
             * El constructor 0 llena la variable de conexion extrayendo de la sesion.
             */
            $lsCodigoFuente->append('       function __construct0()' . $this->csSalto);
            $lsCodigoFuente->append('       {' . $this->csSalto);
            $lsCodigoFuente->append('           $this->coConexion = $this->coSesion->get(\'Conexion\');' . $this->csSalto);
            $lsCodigoFuente->append('        }' . $this->csSalto);
            
            $lsCodigoFuente->append('       function __construct1($poConexion)' . $this->csSalto);
            $lsCodigoFuente->append('       {' . $this->csSalto);
            $lsCodigoFuente->append('           $this->coConexion = $poConexion;' . $this->csSalto);
            $lsCodigoFuente->append('        }' . $this->csSalto);
            
            return $lsCodigoFuente->toString();
        }
        
        function GeneraFuncionesCRUD()
        {
            $lsCodigoFuente = new StringBuilder();
            //$lsQuery = ConsultaEstructuraTabla($psNombreTabla);
            
            /*
             * Se modula la creacion de las funciones para que el código sea mas claro
             */
            
            /*
             * Se generan las funciones del Select
             */
            $lsCodigoFuente->append($this->GeneraSelect());
            
            return $lsCodigoFuente->toString();
            
        }
        
        private function GeneraSelect()
        {
            $lsCodigoFuente = new StringBuilder();
            /*
             * Se generara una funcion simulando la sobrecarga en el select
             * las cuales realizarán las siguientes acciones:
             * - Select() -> La funcion sin parametros devolverá todo el contenido de la tabla.
             * - Select($psWhere) -> La funcion con un parametro recibira las clausulas del where
             *                       devolviendo todos los campos de la tabla.
             * - Select($psCampos,$psWhere) -> La funcion con dos parametros devolvera el contenido
             *                       de los campos seleccionados limitados por la clausula where,
             *                       si se quieren todos los datos el valor de where será de ""(cadena
             *                       vacia)
             * 
             * Los datos generados del select se van a almacenar en una variable que lleva por nombre
             * $coDatos, esta variable tendrá su propiedad para poder acceder a ella desde fuera de 
             * la clase.
             */
            
            /*
             * Se crea la funcion del select general en la que se decide dependiendo
             * del número de parametros cual funcion se ejecuta.
             */
            
                        
            $lsCodigoFuente->append('       public function Select()' . $this->csSalto);
            $lsCodigoFuente->append('       {' . $this->csSalto);
            $lsCodigoFuente->append('           $loParametros = func_get_args();' . $this->csSalto);
            $lsCodigoFuente->append('           $liNumeroParametros = func_num_args();' . $this->csSalto);
            $lsCodigoFuente->append('           $lsNombreConstructor = "Select" . $liNumeroParametros;' . $this->csSalto);
            $lsCodigoFuente->append('           if(method_exists($this, $lsNombreConstructor))' . $this->csSalto);
            $lsCodigoFuente->append('           {' . $this->csSalto);
            $lsCodigoFuente->append('               call_user_func_array(array($this, $lsNombreConstructor), $loParametros);' . $this->csSalto);
            $lsCodigoFuente->append('           }' . $this->csSalto);
            $lsCodigoFuente->append('        }' . $this->csSalto);
            
            /*
             * Select sin parametros
             */
            
            $lsCodigoFuente->append('       private function Select0()');
            $lsCodigoFuente->append('       {' . $this->csSalto);
            $lsCodigoFuente->append('           $lsCampos = new StringBuilder();' . $this->csSalto);
            $lsCodigoFuente->append('           $lsQuery = new StringBuilder()' . $this->csSalto);
            $lsCodigoFuente->append('           foreach($this->CAMPOS as $campo)' . $this->csSalto);
            $lsCodigoFuente->append('           {' . $this->csSalto);
            $lsCodigoFuente->append('               $lsCampos->appendFormat(\'{0},\', [$campo]);' . $this->csSalto);
            $lsCodigoFuente->append('           }' . $this->csSalto);
            $lsCodigoFuente->append('           $lsQuery.appendFormat(\'SELECT {0} FROM {1}\', [$lsCampos->toString(), $this->NOMBRETABLA]);' . $this->csSalto);
            $lsCodigoFuente->append('           $this->coDatos = $this->coConexion->ejecutarQuery($lsQuery->toString())');
            $lsCodigoFuente->append('        }' . $this->csSalto);
            
            /*
             * Select con parametro psWhere
             */
            
            $lsCodigoFuente->append('       private function Select1($psWhere)');
            $lsCodigoFuente->append('       {' . $this->csSalto);
            $lsCodigoFuente->append('           $lsCampos = new StringBuilder();' . $this->csSalto);
            $lsCodigoFuente->append('           $lsQuery = new StringBuilder()' . $this->csSalto);
            $lsCodigoFuente->append('           foreach($this->CAMPOS as $campo)' . $this->csSalto);
            $lsCodigoFuente->append('           {' . $this->csSalto);
            $lsCodigoFuente->append('               $lsCampos->appendFormat(\'{0},\', [$campo]);' . $this->csSalto);
            $lsCodigoFuente->append('           }' . $this->csSalto);
            $lsCodigoFuente->append('           $lsQuery.appendFormat(\'SELECT {0} FROM {1}\', [$lsCampos->toString(), $this->NOMBRETABLA]);' . $this->csSalto);
            $lsCodigoFuente->append('           if($psWhere != \'\')' . $this->csSalto);
            $lsCodigoFuente->append('           {' . $this->csSalto);
            $lsCodigoFuente->append('               $lsQuery.appendFormat(\' WHERE {0}\');' . $this->csSalto);
            $lsCodigoFuente->append('           }' . $this->csSalto);
            $lsCodigoFuente->append('           $this->coDatos = $this->coConexion->ejecutarQuery($lsQuery->toString())' . $this->csSalto);
            $lsCodigoFuente->append('        }' . $this->csSalto);
            
            /*
             * Select con parametro psWhere y psCampos
             */
            
            $lsCodigoFuente->append('       private function Select2($psCampos,$psWhere)');
            $lsCodigoFuente->append('       {' . $this->csSalto);
            $lsCodigoFuente->append('           $lsQuery = new StringBuilder()' . $this->csSalto);
            $lsCodigoFuente->append('           $lsQuery.appendFormat(\'SELECT {0} FROM {1}\', [$lsCampos->toString(), $this->NOMBRETABLA]);' . $this->csSalto);
            $lsCodigoFuente->append('           if($psWhere != \'\')' . $this->csSalto);
            $lsCodigoFuente->append('           {' . $this->csSalto);
            $lsCodigoFuente->append('               $lsQuery.appendFormat(\' WHERE {0}\');' . $this->csSalto);
            $lsCodigoFuente->append('           }' . $this->csSalto);
            $lsCodigoFuente->append('           $this->coDatos = $this->coConexion->ejecutarQuery($lsQuery->toString())' . $this->csSalto);
            $lsCodigoFuente->append('        }' . $this->csSalto);
            
            return $lsCodigoFuente->toString();
        }
        /*
         * Funcion para obtener la consulta que extrae la informacion de la tabla.
         */
        function ConsultaEstructuraTabla($psNombreTabla)
        {
            $lsQuery = new StringBuilder();
            
            $lsQuery->append('SELECT  ');
            $lsQuery->append(' C.COLUMN_NAME, ');
            $lsQuery->append(' C.DATA_TYPE, ');
            $lsQuery->append(' C.CHARACTER_MAXIMUM_LENGTH,');
            $lsQuery->append(' C.IS_NULLABLE, ');
            $lsQuery->append(' C.COLUMN_KEY, ');
            $lsQuery->append(' C.COLUMN_DEFAULT, ');
            $lsQuery->append(' C.EXTRA,');
            $lsQuery->append(' PK.REFERENCED_TABLE_NAME,');
            $lsQuery->append(' PK.REFERENCED_COLUMN_NAME');

            $lsQuery->append(' FROM  ');
            $lsQuery->append(' INFORMATION_SCHEMA.COLUMNS AS C');
            $lsQuery->append(' LEFT JOIN');
            $lsQuery->append(' INFORMATION_SCHEMA.KEY_COLUMN_USAGE AS PK');
            $lsQuery->append(' ON');
            $lsQuery->append(' C.TABLE_SCHEMA = PK.CONSTRAINT_SCHEMA');
            $lsQuery->append(' AND C.TABLE_NAME = PK.TABLE_NAME');
            $lsQuery->append(' AND C.COLUMN_NAME = PK.COLUMN_NAME');
            $lsQuery->append(' AND C.TABLE_CATALOG = PK.TABLE_CATALOG');
            $lsQuery->append(' AND C.TABLE_SCHEMA = PK.TABLE_SCHEMA');
            $lsQuery->append(' AND PK.CONSTRAINT_NAME <> "PRIMARY"');
            $lsQuery->append(' WHERE');
            $lsQuery->appendFormat(' C.TABLE_SCHEMA = "{0}"',array($this->coConexion->csNombreBD));
            $lsQuery->appendFormat(' AND C.TABLE_NAME = "{0}"', array($psNombreTabla));
            
            //echo $lsQuery->toString() . '</br></br>';
            return $lsQuery->toString();
        }
    }
?>
