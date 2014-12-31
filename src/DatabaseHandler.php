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

class DatabaseHandler extends PDO {
  
         
        /**
         * Manipulador de errores
         * @param  [type] $exception [description]
         * @return [type]            [description]
         */
         public static function exception_handler($exception) {
             // Output the exception details
             die('Uncaught exception: '. $exception->getMessage());
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
             restore_exception_handler();
         }

         public function search($key){

            $db = $this->prepare("select Nombre, Tamanho, ftps.direccion_ip as ip, SUBSTRING_INDEX(Nombre, '.', -1) AS ext, path from ftptree INNER JOIN ftps ON ftptree.idftp = ftps.id where nombre LIKE '%".$key."%'");
            $db->execute();

            return $db->fetchAll();;

         }

 }