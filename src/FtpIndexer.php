<?php
/**
 * FtpIndexer
 *  
 * Clase para indexar el contenido de los ftps
 *  
 * @author Jesús Damián García Pérez <jdamian@tic.copextel.com.cu>
 * @license MIT
 * @copyright NetLab
 * @version Git: $Id$
 * 
 */

use \DatabaseHandler;

class FtpIndexer{

	/**
	 * Almacena el objeto DatabaseHandler
	 * @var DatabaseHandler
	 */
	private $dbHandler;

	/**
	 * Conexión ftp
	 * @var [type]
	 */
	private $cnx;

	/**
	 * Almacena los errores al leer un directorio
	 * @var array
	 */
	private $error;

	/**
	 * Variable temporal para almacenar los resultados
	 * @var array
	 */
	private $item;

	/**
	 * Constructor de la clase. Se inyecta el objeto DatabaseHandler
	 * @param  DatabaseHandler $database [description]
	 * @return [type]                    [description]
	 */
	public function __construct(DatabaseHandler $dbHandler)
	{

		$this->dbHandler = $dbHandler;
	}

	/**
	 * Escanea un ftp
	 * @param  integer $ftp_id 
	 * @return array Resultado del escaneo
	 */
	public function scan($ftp_id)
	{
		try{

			// Modificar configuración de php para el proceso de escaneo
			set_time_limit(0);

			/**
			 * Obtener los datos del ftp
			 * @var array
			 */
			$ftp = $this->dbHandler->getFtp($ftp_id);

			/**
			 * No realizar la acción si el ftp se está indexando
			 */
			if($ftp[0]['status'] == 'Indexando...'){
				return [$ftp[0]['direccion_ip'] => ['success' => false, 'message' => 'El ftp se está indexando en estos momentos.'] ];
			}

			/**
			 * No realizar la acción si el ftp está desactivado
			 */
			if(!$ftp[0]['activo']){
				return [$ftp[0]['direccion_ip'] => ['success' => false, 'message' => 'El ftp está desactivado.'] ];
			}

			/**
			 * Loguearse en el ftp. Devuelve true si es correcto.
			 */
			$login = $this->login($ftp[0]['direccion_ip'], $ftp[0]['user'], $ftp[0]['pass']);

			if($login){

				//Cambiar el estado del ftp y setear hora de inicio de escaneo
				$this->dbHandler->updateFtp(array(
					'status'		=> 'Indexando...',
					'hora_inicio'	=> date('g:i:s')
					), $ftp_id);

				//Eliminar los resultados de escaneos previos
				$result_del = $this->dbHandler->deleteScan($ftp_id);
				$this->error = null;

				if($result_del){

					$this->listDetails($this->cnx, "", 0, $ftp_id);

					if(count($this->item) > 0) $this->dbHandler->insertScan($this->item);
			     	//Limpiar la variable
			     	$this->item = null;

					//Cerrar la conexión con el ftp
					ftp_close($this->cnx);

					$estado = count($this->error) == 0 ? 'Indexado' : 'Parcialmente indexado';

					//Setear el estado en el ftp
					$this->dbHandler->updateFtp(array('status' => $estado), $ftp_id);

					$result = ['success' => true, 'message' => $estado];

				}else{
					$result = ['success' => false, 'message' => $result_del];
				}

			}else{
				$result = ['success' => false, 'message' => $login['message']]; 
			}

		}catch(\Exception $e){
			$result = ['success' => false, 'message' => $e->getMessage()];
		} 

		//Actualizar fecha, hora y mensaje del último escaneo
		$this->dbHandler->updateFtp(array(
			'date_last_scan' 	=> date('Y-m-d'),
			'hora_fin'			=> date('g:i:s'),   
			'message' 			=> $result['message']
			 ), $ftp_id);

		return [$ftp[0]['direccion_ip'] => $result];
	}

	/**
	 * Escanea todos los ftps activos 
	 * @return array Resultado del escaneo
	 */
	public function scanAll()
	{
		/**
		 * Obtener los ftps activos
		 * @var array
		 */
		$ftps = $this->dbHandler->getActivesFtps();

		foreach ($ftps as $ftp) {
			$results[$ftp['direccion_ip']] = $this->scan($ftp['id']);
		}

		return $results;

	}

	/**
	 * Crea una conexión a un ftp
	 * @param  string $ftp
	 * @return [type]       [description]
	 */
	private function connect($ftp)
	{

		if($this->cnx = ftp_connect($ftp,21,30)) return $this->cnx;
		
		throw new Exception("Error al conectarse al ftp", 1);

	}

	/**
	 * Cierra la conexión al ftp
	 * @return [type] [description]
	 */
	public function close(){
		ftp_close($this->cnx);
	}


	/**
	 * Loguearse en el ftp
	 * @param  [type] $ftp  [description]
	 * @param  [type] $user [description]
	 * @param  [type] $pass [description]
	 * @return [type]       [description]
	 */
	private function login($ftp, $user, $pass){
		try{

			$cnx = $this->connect($ftp);

			if($login = @ftp_login($cnx,$user,$pass)) return $login;

			throw new Exception("No se pudo acceder al ftp con las credenciales suministradas.", 1);

		}catch(\Exception $e){ throw $e; }
	}

	/**
	 * Obtiene la lista de objectos del ftp y los inserta en la base de datos
	 * @param  [type] $cnx   Conexión ftp
	 * @param  string $dir   Directorio
	 * @param  integer $level Nivel de profundidad
	 * @param  integer 
	 * @return array 
	 */
	private function listDetails($cnx, $directory = "", $profundidad = 0, $ftp_id){

		if (is_array($children = @ftp_rawlist($cnx, $directory))) { 

			foreach ($children as $child) { 

            	$array = $chunks = preg_split("/\s+/", $child);

            	//Obtener el nombre primero
            	array_splice($array, 0, 8);
        		$item['name'] = implode(" ", $array) ;	

            	if($chunks[0]{0} === 'd' && $item['name'] != "." && $item['name'] != ".." ){
            		$this->listDetails($cnx, $directory."/".$item['name'], $profundidad + 1, $ftp_id);
            	}else if($chunks[0]{0} === '-'){
            		
            		list($i['rights'], $i['number'], $i['user'], $i['group'], $i['size'], $i['month'], $i['day'], $i['time']) = $chunks;
            		
            		$fecha = new \DateTime($i['month'].' '.$i['day']. ' '.$i['time']);
            		$item['fecha'] = $fecha->format('Y-m-d');
            		$item['size'] = $i['size'];
            		$item['profundidad'] = $profundidad;
            		$item['path'] = $directory."/".$item['name'];
            		if(preg_match('/\./', $item['name'])){
            			$item['ext'] = substr($item['name'],strrpos($item['name'], '.') + 1);
            		}else{
            			$item['ext'] = '';	
            		}
            		$item['ftp_id'] = $ftp_id;

            		$this->item[] = $item;
                	
            	}

            	//Si se han guardado 300 elementos en el array...
            	if(count($this->item) == 300 ){
            		//Insertarlos en la base de datos
            		$this->dbHandler->insertScan($this->item);
            		//Limpiar la variable
            		$this->item = null;
            	}
             } 

         }else{

         	$this->error[] = "Error leyendo el directorio ". $directory;
         	return false;
         	
         } 

         return true;

	}

}
