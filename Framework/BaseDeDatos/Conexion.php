
<?php
/*
* La clase de conexion servirá de interfaz para realizar una conexion a diferentes
* motores de base de datos. Su funcion será fungir como interfaz de conexion generica
* para facilitar el manejo de las conexiones de base de datos
*/
    require_once (__DIR__ . '/../Enumeraciones/enumMotoresBD.php');
    require_once (__DIR__ . '/../BaseDeDatos/ConexionMySQL.php');
    require_once (__DIR__ . '/../BaseDeDatos/ConexionSQLServer.php');
    class Conexion
    {
        /*
         * Declaracion de variables generales de clase de conexion, con la información necesaria para
         * establecer la conexion con la Base de Datos
         */
        var $csURLServidor;
        var $csUsuario;
        var $csClave;
        var $csNombreBD;
        var $ciMuestraErrores;
        var $ciMotorBD;
        /*
         * Declaracion de variable que funge como enumeracion para la eleccion de el motor de Base de datos
         */
        var $coEnmMotorBD;
        
        /*
         * Declaracion de variable de conexion generica
         */
        
        var $coConexion;
        
        /*
         * Declaracion de variables de errores
         */
        var $csErrorSQL;
        /*
         * Constructor
         */
        public function __construct() {
            /*Se inicializa la clase de enumeracion de bases de datos*/
            $this->coEnmMotorBD = new MotoresBD();
            
            /*Se inicializa el objeto de conexion en null*/
            $this->coConexion = NULL;
            /*
             * Se emulará una sobrecarga de métodos para podertener distintos constructores
             */
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
                 
        function __construct0()
        {
            //se inicializan las variables vacias cuando no se pasan parametros
            $this->csURLServidor = '';
            $this->csUsuario = '';
            $this->csClave = '';
            $this->csNombreBD = '';
            $this->ciMuestraErrores = '';
            $this->ciMotorBD = 0;
        }
        
        function  __construct5($psServidor, $psUsuario, $psClave, $psNombreBD, $psMotorBD)
        {
            //se inicializan las variables con los parametros parados por la funcion
            $this->csURLServidor = $psServidor;
            $this->csUsuario = $psUsuario;
            $this->csClave = $psClave;
            $this->csNombreBD = $psNombreBD;
            $this->ciMuestraErrores = 0;
            //en el caso de el motor de base de datos se invoca esta funcion para seleccionar internamente el motor
            $this->SeleccionaMotorBD($psMotorBD);
        }       
        
        function  __construct6($psServidor, $psUsuario, $psClave, $psNombreBD, $psMotorBD, $piMuestraErrores)
        {
            //se inicializan las variables con los parametros parados por la funcion
            $this->csURLServidor = $psServidor;
            $this->csUsuario = $psUsuario;
            $this->csClave = $psClave;
            $this->csNombreBD = $psNombreBD;
            $this->ciMuestraErrores = $piMuestraErrores;
            //en el caso de el motor de base de datos se invoca esta funcion para seleccionar internamente el motor
            $this->SeleccionaMotorBD($psMotorBD);
        }       
        
        //funcion de prueba para mostrar los valores de la funcion
        function MuestraValores()
        {
            echo 'Servidor='. $this->csURLServidor . '</br>';
            echo 'Usuario='. $this->csUsuario . '</br>';
            echo 'Clave='. $this->csClave . '</br>';
            echo 'NombreBD='. $this->csNombreBD . '</br>';
            echo 'MuestraErrores='. $this->ciMuestraErrores . '</br>';
            echo 'MotorDB='. $this->ciMotorBD . '</br>';
        }
        
        /*
         * Funcion para seleccionar el motor de base de datos a utilizar de acuerdo a una cadena indicada en el constructor
         * Los motores soportados por el momento son:
         *  -> MySQL
         *  -> SQLServer
         */
        function SeleccionaMotorBD($psMotor)
        {
            switch (strtoupper($psMotor))
            {
                case 'MYSQL':
                    $this->ciMotorBD = $this->coEnmMotorBD->MySQL;
                    break;
                case 'SQLSERVER':
                    $this->ciMotorBD = $this->coEnmMotorBD->SQLServer;
                    break;
            }
            
        }
        
        function ValidaDatosConexion()
        {
            $this->csErrorSQL = '';
            if ($this->ciMotorBD == 0) 
            {
                $this->csErrorSQL .= ' Error: No se ha establecido un motor de Base de datos.';           
            }
            
            if ($this->csURLServidor == '' || $this->csURLServidor == NULL)
            {
                $this->csErrorSQL .= ' Error: No se ha definido la URL del host de Base de datos.'; 
            }
            
            if ($this->csUsuario == '' || $this->csUsuario == NULL)
            {
                $this->csErrorSQL .= ' Error: No se ha definido el usuario de Base de datos.'; 
            }
            
            if ($this->csClave == '' || $this->csClave == NULL)
            {
                $this->csErrorSQL .= ' Error: No se ha definido la contraseña de Base de datos.'; 
            }
            
            if ($this->csNombreBD == '' || $this->csNombreBD == NULL)
            {
                $this->csErrorSQL .= ' Error: No se ha definido el nombre de Base de datos.'; 
            }
            
            //echo 'Errores: '. $this->csErrorSQL;
        }
        
        function InicializaObjetoConexion()
        {
            switch ($this->ciMotorBD) 
            {
                case $this->coEnmMotorBD->MySQL:    
                    $this->coConexion = new ConexionMySQL($this->csURLServidor, $this->csUsuario, $this->csClave, $this->csNombreBD, $this->ciMuestraErrores);
                    break;
                case $this->coEnmMotorBD->SQLServer;
                    $this->coConexion = new ConexionSQLServer($this->csURLServidor, $this->csUsuario, $this->csClave, $this->csNombreBD, $this->ciMuestraErrores);
                    break;
                    
                default:
                    break;
            }
        }
                
        function Conectar()
        {
            if($this->coConexion == NULL)
            {
                //echo 'Inicio conexion';
                $this->ValidaDatosConexion();
                echo $this->csErrorSQL;
                if ($this->csErrorSQL == '')
                {
                    $this->InicializaObjetoConexion();
                }
                
            }
            return $this->csErrorSQL;
            //this->coConexion->desconectar();
            //echo $state = $this->coConexion->getState();
            
        }
        
        function NumeroDeRegistros($psConsulta)
        {
            $this->Conectar();
            return $this->coConexion->numeroRegistros($psConsulta);
        }
        
        
        function EjecutarQuery($psConsulta, $pbStatus=false)
        {
            $this->Conectar();
            return $this->coConexion->ejecutarQuery($psConsulta, $pbStatus);
        }
        
        function EjecutarEscalar($psConsulta, $pbStatus=false)
        {
            $this->Conectar();
            return $this->coConexion->ejecutarEscalar($psConsulta, $pbStatus);
        }
    }
?>