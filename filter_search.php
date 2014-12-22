<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Jordy
 * Date: 20/12/14
 * Time: 12:26
 * To change this template use File | Settings | File Templates.
 */

include 'config.php';//Inclusion del archivo de configuracion que contiene los datos de coneccion al server
include 'include\general.php';//inclusion del archivo de funciones basicas a la bd
$coneccion=mysql_connect($server,$user,$password);//inicializa la coneccion
$db=mysql_select_db("ftpindexer");//selecciona la bd

//todo Por hacer: hay que crear un campo para almecenar el nombre de la bd en el archivo config.php para evitar
//tener que actualizarlo en todos los archivos



//if(isset($_GET['filter_search']))
if($_GET)
{
    $response= array();

if(isset($_GET["searchedtext"]) && strlen($_GET["searchedtext"])<1)
{
    $response['code']='ERROR';
}
else if(isset($_GET["searchedtext"])){

    $response['code']='OK';
    $ftps_array = array();
    $response['ftps'] = $ftps_array;

    $ftps=mysql_query("select * from ftps where activo=1");
    $ftpscount=mysql_num_rows($ftps);
    $rescount=0;
    if($ftpscount!=0){
//        echo("<div id='all-ftp'>");
        //            echo('<ul class="list-group">');
        for($i=0;$i<$ftpscount;$i++)
        {
            $ftpsrow=mysql_fetch_array($ftps);
            $currentftp=$ftpsrow['user'].'@'.$ftpsrow['direccion_ip'];
            $ftp_object = array();
            $ftp_object['name']=$currentftp;
            $results_array = array();


            $result=mysql_query("select * from ftptree where nombre LIKE '%".strtr(addslashes($_GET["searchedtext"]),' ','%')."%' and idftp=".$ftpsrow['id']);
            $count=mysql_num_rows($result);
            if($count!=0)
            {

                for($j=0;$j<$count;$j++)
                {
                    $row=mysql_fetch_array($result);
                    $rescount++;
                    $result_object = "<a href='ftp://".$ftpsrow['user'].'@'.$ftpsrow['direccion_ip'].$row['path'].'/'.$row['Nombre']."' target='_blank'>".$row['path'].'/'.$row['Nombre']."</a><br>";
                    array_push($results_array,  urlencode($result_object));
//                    echo("<a href='ftp://".$ftpsrow['user'].'@'.$ftpsrow['direccion_ip'].$row['path'].'/'.$row['Nombre']."' target='_blank'>".$row['path'].'/'.$row['Nombre']."</a><br>");
                }

            }
            $ftp_object['results']= $results_array;
            array_push($response['ftps'],  $ftp_object);
        }

    }
    $response['cant_result']=$rescount;
}
    echo '<b>WITHOUT ENCODED</b><br/>';
    var_dump($response);
    $encode = json_encode($response);
    echo '<br/><br/><b>ENCODED</b><br/>';
    var_dump($encode);
//    echo $encode;

}

mysql_close($coneccion);

?>