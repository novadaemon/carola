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

class FtpIndexer()
{

	/**
	 * Almacena el objeto DatabaseHandler
	 * @var DatabaseHandler
	 */
	private $dbHandler;

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
	public function scan($ftp_id);
	{
		try{

			/**
			 * Obtener los datos del ftp
			 * @var array
			 */
			$ftp = $this->dbHandler->getFtp($ftp_id);

			/**
			 * Loguearse en el ftp. Devuelve true si es correcto.
			 */
			if($this->login($ftp['direccion_ip'], $ftp['user'], $ftp['pass'])){

				//....escanearlo

			}

		}catch(\Exception $e){
			$result = array('success' => false, 'message' => $e->getMessage());
		} 
	}

	/**
	 * Escanea todos los ftps activos 
	 * @return array Resultado del escaneo
	 */
	public function scanAll()
	{
		# code...
	}

	/**
	 * Crea una conexión a un ftp
	 * @param  string $ftp
	 * @return [type]       [description]
	 */
	private function connection($ftp)
	{
		$cnx = ftp_connect($ftp);

		if($cnx){
			return $cnx;
		}else{
			throw new Exception("Error al conectarse al ftp", 1);
		}

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

			$cnx = $this->connection($ftp);

			return @ftp_login($cnx,$user,$pass);

		}catch(\Exception $e) throw $e;
	}

}