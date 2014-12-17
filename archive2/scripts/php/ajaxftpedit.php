<?php
include '../../config.php';
include '../../include/general.php';
$coneccion=mysql_connect($server,$user,$password);
$db=mysql_select_db("ftpindexer");
if($_POST)
{
 $aditionalinformation="";
	if($_POST["action"]=="insert" || $_POST["action"]=="edit")
	{
		if($_POST["descripcion"]==NULL)
		{
			$arr = array('result' => 'bad','error'=>'El valor del campo "Descripcion" no puede estar vacio, intentelo de nuevo con los datos correctos.');
			echo json_encode($arr);
			return;
		}
		if($_POST["direccion"]==NULL)
		{
			$arr = array('result' => 'bad','error'=>'El valor del campo "Direccion" no puede estar vacio, intentelo de nuevo con los datos correctos."');
			echo json_encode($arr);
			return;
		}
		if($_POST["activo"]=="false")
			$aditionalinformation=$aditionalinformation."<br>-El nuevo FTP esta marcado como inactivo, por lo que no sera utilizado en las busquedas de los usuarios hasta que se marque como \"activo\"";
		if($_POST["user"]==NULL)
			{
				$aditionalinformation=$aditionalinformation."<br>-No se especifico un usuario para el proceso de identificacion, se utilizara \"anonymous\" como usuario predeterminado para realizar la indizacion";
				$_POST["user"]="anonymous";
			}
		if($_POST["pass"]==NULL)
			{
				if($_POST["user"]=="anonymous")
					{
						$aditionalinformation=$aditionalinformation."<br>-Se utilizara como contrasena \"anonymous@ftpindexer.cu\" para realizar la indizacion.";
						$_POST["pass"]="anonymous@ftpindexer.cu";
					}
				else
					{
						$aditionalinformation=$aditionalinformation."<br>-No se ha especificado una contrasena para realizar la coneccion con este ftp, se utilizara una contrasena en blanco para realizar la indizacion.";
						$_POST["pass"]="";
					}	
			}
			$lastid=-1;
			if($_POST["activo"]=="true")
			{$activar=1;$checked="checked";}
			else
			{$activar=0;$checked="";}
		if($_POST["action"]=="insert")
		{			
			$result=mysql_query("insert into ftps (descripcion,direccion_ip,activo,user,pass) values ('".$_POST["descripcion"]."','".$_POST["direccion"]."',".$activar.",'".$_POST["user"]."','".$_POST["pass"]."')");
			if($result==true)
			{
				$result2=mysql_query("SELECT MAX(id) as lastid from ftps");
				if($result2==true)
				{
					$count=mysql_num_rows($result2);
					if($count==1)
					{
						$row=mysql_fetch_array($result2);
						$lastid=$row['lastid'];
					}
				}
			}		
			if($aditionalinformation!="")
				$aditionalinformation="<br><b>Informacion adicional:</b>".$aditionalinformation;
			if($result==false)
				$arr = array('result' => 'bad','error'=>'No se ha podido ejecutar la consulta SQL en el servidor de datos.<br>'."insert into ftps (descripcion,direccion_ip,activo,user,pass) values ('".$_POST["descripcion"]."','".$_POST["direccion"]."',".$activar.",'".$_POST["user"]."','".$_POST["pass"]."')",'myid'=>$lastid);
			else
				$arr = array('result' => 'ok','aditionalinformation'=>$aditionalinformation,'newrow'=>"<tr id='rowid".$lastid."'><td><a href='#' onclick='ShowEditFTPDialog(".$lastid.")'><img src=\"media/png/edit.png\"></a>&nbsp;&nbsp;<a href='#' onclick='ShowDeleteFTPDialog(".$_POST['actionid'].")'><img src=\"media/png/delete.png\"></a></td><td class=\"text-center\"><input type=\"checkbox\" ".$checked."></td><td>".$_POST["descripcion"]."</td><td>".$_POST["direccion"]."</td><td>".$_POST["user"]."</td><td id='row".$lastid."statustd'>No indizado (<a href='#' onclick='IndizarFTP(".$lastid.",false)'>Indizar</a>)</td></tr>");
			echo json_encode($arr);
		}
		else if($_POST["action"]=="edit")
		{
			$result=mysql_query("update ftps set descripcion='".$_POST["descripcion"]."',direccion_ip='".$_POST["direccion"]."',activo=".$activar.",user='".$_POST["user"]."',pass='".$_POST["pass"]."' where id='".$_POST["actionid"]."'");		
			if($aditionalinformation!="")
				$aditionalinformation="<br><b>Informacion adicional:</b>".$aditionalinformation;
			if($result==false)
				$arr = array('result' => 'bad','error'=>'No se ha podido ejecutar la consulta SQL en el servidor de datos.<br>'."update ftps set descripcion='".$_POST["descripcion"]."',direccion_ip='".$_POST["direccion"]."',activo=".$activar.",user='".$_POST["user"]."',pass='".$_POST["pass"]."' where id='".$_POST["actionid"]."'");
			else
				$arr = array('result' => 'ok','aditionalinformation'=>$aditionalinformation,'editedrow'=>"<td id='rowid".$_POST["actionid"]."'><a href='#' onclick='ShowEditFTPDialog(".$_POST["actionid"].")'><img src=\"media/png/edit.png\"></a>&nbsp;&nbsp;<a href='#' onclick='ShowDeleteFTPDialog(".$_POST['actionid'].")'><img src=\"media/png/delete.png\"></a></td><td class=\"text-center\"><input type=\"checkbox\" ".$checked."></td><td>".$_POST["descripcion"]."</td><td>".$_POST["direccion"]."</td><td>".$_POST["user"]."</td>".$_POST["statustd"]);
			echo json_encode($arr);																                     //echo("<tr id='rowid".$row['id']."'><td><a href='#' onclick='ShowEditFTPDialog(".$row['id'].")'><img src=\"media/png/edit.png\"></a>&nbsp;&nbsp;<a href='#' onclick='ShowDeleteFTPDialog(".$row['id'].")'><img src=\"media/png/delete.png\"></a></td><td class=\"text-center\"><input type=\"checkbox\" id=\"activeforindexing\" ".$activo."></td><td>".$row['descripcion']."</td><td>".$row['direccion_ip']."</td><td>".$row['user']."</td><td id='row".$row['id']."statustd'>".$row['status'].$extrastatus."</td></tr>");	
		}
	}
	else if($_POST["action"]=="delete")
	{
		$result=mysql_query("select * from tasks where idftprelacionado=".$_POST["ftptodelete"]);			
			if(mysql_num_rows($result)!=0)
			{
				$taskrow=mysql_fetch_array($result);
				if($taskrow['Estado']!='Finalizada')
				{
					raiseerror("El FTP que usted ha seleccionado para eliminar esta relacionado con una tarea activa. Intentelo de nuevo una vez finalizada esta.<br><br><b>Nota: </b>Para desactivar una tarea cierre su cuadro de notificacion de estado. Si han ocurrido errores de indizacion, actualice la pagina despues de cerrar la tarea para ver el estado real de los FTPs.");
					exit();
				}
			}
		SqlEx("delete from ftptree where idftp=".$_POST["ftptodelete"]);
		$result=mysql_query("delete from ftps where id=".$_POST["ftptodelete"]);	
		if($result==true)	
			$arr = array('result' => 'ok');
		else
			$arr = array('result' => 'bad','error'=>mysql_error()."<br>La consulta fallida fue: delete from ftps where id=".$_POST["ftptodelete"]);
		echo json_encode($arr);
	}
	else
	{
		$arr = array('result' => 'bad','error'=>'Este script solo puede ser llamado para insertar o editar FTPs. Error en los parametros de entrada.');
		echo json_encode($arr);
	}
}
if($_GET)
{
	if($_GET["action"]=="getrowbyid")
	{
		$result=mysql_query("select * from ftps where id=".$_GET["id"]);
		$row=mysql_fetch_array($result);
		$arr = array('result'=>'ok','activo' => $row['activo'],'descripcion'=>$row['descripcion'],'direccion'=>$row['direccion_ip'],'user'=>$row['user'],'pass'=>$row['pass']);
		echo json_encode($arr);
	}
	else if($_GET["action"]=="dismisstask")
	{
		SqlEx("delete from tasks where id=".$_GET["idtask"]);
		$arr = array('result' => 'ok');
		echo json_encode($arr);
	}
	else if($_GET["action"]=="canceltask")
	{
		SqlEx("update tasks set abortar=1 where id=".$_GET["idtask"]);
		$arr = array('result' => 'ok');
		echo json_encode($arr);
	}
}
?>
