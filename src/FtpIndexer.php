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
	 * Variable para almacenar los archivos del ftp
	 * @var array
	 */
	private $items;

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

			/**
			 * Obtener los datos del ftp
			 * @var array
			 */
			$ftp = $this->dbHandler->getFtp($ftp_id);

			/**
			 * Loguearse en el ftp. Devuelve true si es correcto, ecxcepción en caso contrario.
			 */
			$login = $this->login($ftp[0]['direccion_ip'], $ftp[0]['user'], $ftp[0]['pass']);
			if($login){

				$datas = $this->listDetails($this->cnx);

				if(isset($datas) && count($datas) > 0 ){

					//Cambiar el estado del ftp
					$this->dbHandler->updateFtp(array('status' => 'Indexando...'), $ftp_id);

					//Eliminar los resultados de escaneos previos
					$result_del = $this->dbHandler->deleteScan($ftp_id);
					
					if($result_del){

						foreach ($datas as $data) {

							//Verificar si no hay error en los datos
							if(isset($data['error'])){

								$error[] = $data['message'];

							}else{

								//insertar el ftp_id en el array
								$data['ftp_id'] = $ftp_id;	

								$result_insert = $this->dbHandler->insertScan($data);
								if(isset($result_insert['error'])){
									$error[] = $result_insert['message']; 
								}
							}
							
						}

						//Comprobar si hay errores en el proceso de insercción en la bd
						$estado = isset($error) ? 'Parcialmente indexado' : 'Indexado';

						//Setear el estado
						$this->dbHandler->updateFtp(array('status' => $estado), $ftp_id);

						$result = array('success' => true, 'message' => $estado);

					}else{
						$result = array('success' => false, 'message' => $result_del );
					}

				}else{
					$result = array('success' => false, 'message' => 'No hay datos para indexar.');
				}
			}else{
				$result = array('success' => false, 'message' => $login['message']); 
			}

		}catch(\Exception $e){
			$result = array('success' => false, 'message' => $e->getMessage());
		} 

		return array($ftp[0]['direccion_ip'] => $result);
	}

	/**
	 * Escanea todos los ftps activos 
	 * @return array Resultado del escaneo
	 */
	public function scanAll()
	{
		/**
		 * Obtener los ftps activo
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

		if($this->cnx = ftp_connect($ftp,21,20)) return $this->cnx;
		
		throw new Exception("Error al conectarse al ftp", 1);

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
	 * Obtiene la lista de objetos de un ftp
	 * @param  [type] $cnx   Conexión ftp
	 * @param  string $dir   Directorio
	 * @param  integer $level Nivel de profundidad
	 * @param  integer 
	 * @return array 
	 */
	private function listDetails($cnx, $directory = "", $profundidad = 0){

		if (is_array($children = @ftp_rawlist($cnx, $directory, true))) { 

            foreach ($children as $child) { 

            	$array = $chunks = preg_split("/\s+/", $child);

            	//Optener le nombre primero
            	array_splice($array, 0, 8);
        		$item['name'] = implode(" ", $array) ;	

            	if($chunks[0]{0} === 'd'){
            		$this->listDetails($cnx, $directory."/".$item['name'], $profundidad + 1);
            	}else{
            		
            		list($i['rights'], $i['number'], $i['user'], $i['group'], $i['size'], $i['month'], $i['day'], $i['time']) = $chunks;
            		
            		$fecha = new \DateTime($i['month'].' '.$i['day']. ' '.$i['time']);
            		$item['fecha'] = $fecha->format('Y-m-d');
            		$item['size'] = $i['size'];
            		$item['profundidad'] = $profundidad;
            		$item['path'] = $directory."/".$item['name'];
            		$item['ext'] = substr($item['name'],strrpos($item['name'], '.') + 1);
            		
                	$this->items[] = $item;
            	}

             } 

         }else{

         	$this->items[] = array('error' => true, 'message' => "Error leyendo el directorio ". $directory);
         	
         } 

         return $this->items;

	}

}