<?php 
          
  include 'config.php';
  include 'include\general.php';
  $coneccion=mysql_connect($server,$user,$password);
  $db=mysql_select_db("ftpindexer");
  if($_POST)
  {     

              $arr= array();
              $text = $_POST["text"];
              // $ftps=mysql_query("select * from ftps where activo=1"); 
              // $ftpscount=mysql_num_rows($ftps);
              $rescount=0;
              // if($ftpscount!=0){
                
              //   for($i=0;$i<$ftpscount;$i++)
              //   {
              //     $ftpsrow=mysql_fetch_array($ftps);
                  //$currentftp=$ftpsrow['user'].'@'.$ftpsrow['direccion_ip'];
                  //echo("select * from ftptree where nombre LIKE '%".strtr(addslashes($_GET["searchedtext"]),' ','%')."%' and idftp=".$ftpsrow['id']);
                  /// en la consulta solo pido 10
                  // $result=mysql_query("select * from ftptree where nombre LIKE '%".strtr(addslashes($text),' ','%')."%' and idftp=".$ftpsrow['id']." LIMIT 10");  
                  $result=mysql_query("select DISTINCT Nombre from ftptree where nombre LIKE '%".strtr(addslashes($text),' ','%')."%' LIMIT 10");
                  $count=mysql_num_rows($result);
                  if($count!=0)
                  {
                    
                    //echo("<p><b>".$currentftp."</b></p>");
                    for($j=1;$j<=$count;$j++)
                    {
                      $row=mysql_fetch_array($result);
                      $isset = false; ///> se encarga de verificar que un archivo 
                      ///que se va a mostrar como sugerencia no sea repetido 
                      ///en los resultados dado que puede estar en distintos ftp
                      foreach ($arr as $key => $value) {
                        if($value == $row['Nombre']){
                          $isset = true;
                          break;
                        }
                      }
                      if(!$isset){
                        array_push($arr, $row['Nombre']);
                        $rescount++;
                      }
                      //echo("\"".$row['Nombre']."\","); 
                    }
                  }
                  
                    
              //   }
              // }
              

              echo json_encode($arr);
              
  }
  mysql_close($coneccion);
             
?>