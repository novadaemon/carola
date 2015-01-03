<?php
/**
 * DatabaseHandler
 *  
 * Clase para manipular la base de datos
 *  
 * @author Jesús Damián García Pérez <jdamian@tic.copextel.com.cu>
 * @license MIT
 * @copyright NetLab
 * @version Git: $Id$
 * 
 */
class DatabaseHandler extends \PDO {
  
         
        /**
         * Manipulador de errores
         * @param  [type] $exception [description]
         * @return [type]            [description]
         */
         public static function exception_handler($exception) {
             // Output the exception details
             return array('error'=> false, 'message' => $exception->getMessage());
         }
  
         /**
          * Constructor de la clase
          * @param string $dsn            data source name
          * @param string $username       usuario
          * @param string $password       password
          * @param array  $driver_options opciones del driver
          */
         public function __construct($dsn, $username='', $password='', $driver_options=array()) {

             // Temporarily change the PHP exception handler while we . . .
             set_exception_handler(array(__CLASS__, 'exception_handler'));

             // . . . create a PDO object
             parent::__construct($dsn, $username, $password, $driver_options);

             // Change the exception handler back to whatever it was before
             // restore_exception_handler();
         }

         /**
          * Método para la búsqueda principal
          * @param  string $key Palabra clave a buscar
          * @return array 
          */
         public function search($key){

            $db = $this->prepare("select Nombre, Tamanho, ftps.direccion_ip as ip, SUBSTRING_INDEX(Nombre, '.', -1) AS ext, path from ftptree INNER JOIN ftps ON ftptree.idftp = ftps.id where nombre LIKE '%".$key."%'");
            $db->execute();

            return $db->fetchAll();;

         }

          /**
           * Método para el autocompletamiento
           * @param  string $key Palabra clave a buscar
           * @return array
           */
          public function autocomplete($key){

            $db = $this->prepare("select DISTINCT Nombre from ftptree where nombre LIKE '%".$key."%' LIMIT 10");
            $db->execute();

            $results = array();

            foreach ($db->fetchAll() as $value) {
                $results[] = $value['Nombre'];
            }

            return array_unique($results);

         }

         /**
          * Buscar todos los ftps
          * @return array
          */
         public function getFtps(){

            $db = $this->prepare("SELECT * FROM ftps");
            $db->execute();

            return $db->fetchAll();

         }

         /**
          * Obtiene los ftps activo
          * @return array
          */
         public function getActivesFtps(){

            $db = $this->prepare("SELECT * FROM ftps WHERE activo = 1");
            $db->execute();

            return $db->fetchAll();

         }

         /**
          * Obtiene los datos de un ftp a partir del id
          * @param integer $id 
          * @return array
          */
         public function getFtp($id){

            $db = $this->prepare("SELECT * FROM ftps WHERE id = :id");
            $db->bindParam(':id', $id, PDO::PARAM_INT);
            $db->execute();

            return $db->fetchAll();

         }

         /**
          * Obtiene los datos de un ftp a partir de la ip
          * @param string $ip 
          * @return array
          */
         public function getFtpByIp($ip){

            $db = $this->prepare("SELECT * FROM ftps WHERE direccion_ip = :ip");
            $db->bindParam(':ip', $ip, PDO::PARAM_STR);
            $db->execute();

            return $db->fetchAll();

         }

         /**
          * Insertar nuevo ftp
          * @param array $data
          * @return string
          */
         public function insertFtp($data){

            try{
                
              $db = $this->prepare("INSERT INTO ftps(descripcion, direccion_ip, activo, user, pass) VALUES(?,?,?,?,?);");

              $db->execute(array_values($data));
              return $db->errorInfo();   

            }catch(\Exception $e){
                return $this->exception_handler($e);
            }

         }

         /**
          * Actualiza un ftp
          * @param array $data
          * @param integer $id
          * @return string
          */
         public function updateFtp($data, $id){

            foreach (array_keys($data) as $key) {
              $fields[] = $key . '= ?';
            }

            try{
                
                $db = $this->prepare("UPDATE ftps SET ". join(",", $fields). " WHERE id =". $id .";");

                $db->execute(array_values($data));
                return $db->errorInfo();   

            }catch(\Exception $e){
                return $this->exception_handler($e);
            }

         }

         /**
          * Elimina un ftp
          * @param integer $id
          * @return string
          */
         public function deleteFtp($id){

            try{
                
                $db = $this->prepare("DELETE from ftps WHERE id = ?");
                $db->bindParam(1, $id, PDO::PARAM_INT);
                $db->execute();
                return $db->errorInfo();   

            }catch(\Exception $e){
                return $this->exception_handler($e);
            }

         }

         /**
          * Insertar datos del escaneo
          * @param array $data
          * @return string
          */
         public function insertScan($data){

            try{
                
              $db = $this->prepare("INSERT INTO ftptree(Nombre, Fecha, Tamanho, profundidad, path, ext, idftp) VALUES(?,?,?,?,?,?,?);");

              $db->execute(array_values($data));
              
              if($db->errorInfo()[0] == '00000') return true;

              throw new \Exception($db->errorInfo()[2], 1);   

            }catch(\Exception $e){
                return $this->exception_handler($e);
            }

         }

         /**
          * Elimina el escaneo de un ftp
          * @param integer $ftp_id
          * @return string
          */
         public function deleteScan($ftp_id){

            try{
                
                $db = $this->prepare("DELETE from ftptree WHERE idftp = ?");
                $db->bindParam(1, $ftp_id, PDO::PARAM_INT);
                $db->execute();
                
                if($db->errorInfo() == '00000') return true;

                return $db->errorInfo();

                throw new \Exception($db->errorInfo()[2], 1);
                    

            }catch(\Exception $e){
                return $this->exception_handler($e);
            }

         }

 }