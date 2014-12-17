<?php
include '../../config.php';
include '../../include/general.php';
$coneccion=mysql_connect($server,$user,$password);
$db=mysql_select_db("ftpindexer");
$lastid=-1;
if($_GET)
	{
		if($_GET["action"]=="scansingle")
		{
			ignore_user_abort(true);
			$result=mysql_query("select * from tasks where idftprelacionado=".$_GET["scanid"]);			
			if(mysql_num_rows($result)!=0)
			{
				$taskrow=mysql_fetch_array($result);
				if($taskrow['Estado']=='Finalizada')
					SqlEx("delete from tasks where id=".$taskrow['id']);
				else
				{raiseerror("El FTP que usted ha seleccionado para indizar esta relacionado con una tarea activa. Intentelo de nuevo una vez finalizada esta.<br><br><b>Nota: </b>Para desactivar una tarea cierre su cuadro de notificacion de estado. Si han ocurrido errores de indizacion, actualice la pagina despues de cerrar la tarea para ver el estado real de los FTPs.");
				return;}
			}
			SqlEx("insert into tasks (idftprelacionado,estado,progreso) values (".$_GET["scanid"].",'Estado: Esperando respuesta...','Progreso: Esperando respuesta...')",false,"No se ha podido bloquear el FTP seleccionado para iniciar una nueva tarea. Consulte a su administrador.");
			$result=mysql_query("SELECT MAX(id) as lastid from tasks");
			$rss=mysql_fetch_array($result);
			$lastid=$rss['lastid'];
			
			SqlEx("update global set value='active' where name='status'");
			if($_GET["remake"]==true)
			{
				SqlEx("delete from ftptree where idftp=".$_GET["scanid"]);
				SqlEx("update ftps set status='No indizado' where id=".$_GET["scanid"]);
				SqlEx("update tasks set progreso='Eliminando indice...' where id=".$lastid);
			}
			set_time_limit(0);
			$result=mysql_query("select * from ftps where id=".$_GET["scanid"]);
			SqlEx("update ftps set status='Indizando...' where id=".$_GET["scanid"]);
			SqlEx("update tasks set estado='Activa', progreso='...' where id=".$lastid);
			$count=mysql_num_rows($result);
			//echo($count);
			$err=false;
			$error="";
			for($i=0;$i<$count;$i++)
			{		
				$progress="";
				$row=mysql_fetch_array($result);	
				SqlEx("update tasks set descripcion='Indizacion del FTP: ".$row['descripcion']."' where id=".$lastid);
				$progress=$progress."Conectando a: ".$row['direccion_ip']."<br>";
				SqlEx("update ftps set progress='".$progress."' where id=".$_GET["scanid"]);
				SqlEx("update tasks set progreso='".$progress."' where id=".$lastid);
				$connection_id=ftp_connect($row['direccion_ip'],21,20);
				if($connection_id==false)
				{
					$err=true;
					$error="No se pudo conectar";
					SqlEx("update ftps set status='No indizado', progress='' where id=".$_GET["scanid"]." ");
					SqlEx("update tasks set Estado='Finalizada con errores', Progreso='No se pudo conectar.' where id=".$lastid);					
					break;
				}
				$progress=$progress."Autentificando...<br>";
				SqlEx("update ftps set progress='".$progress."' where id=".$_GET["scanid"]);
				SqlEx("update tasks set progreso='".$progress."' where id=".$lastid);
				if(@ftp_login($connection_id,$row['user'],$row['pass']))
					$progress=$progress."Conectado<br>";
				else
				{
					$progress=$progress."No se pudo conectar<br>";
					$err=true;
					$error="No se ha podido entrar al FTP con el usuario y contrasena especificados.";
					SqlEx("update ftps set status='No indizado', progress='' where id=".$_GET["scanid"]." ");
					SqlEx("update tasks set Estado='Finalizada con errores' where id=".$lastid);					
					break;
				}
				SqlEx("update ftps set progress='".$progress."' where id=".$_GET["scanid"]);
				SqlEx("update tasks set progreso='".$progress."' where id=".$lastid);
				$progress=$progress."Indizando directorio: ";
				if(!IndexFtpDirectory($progress,$connection_id,"",$_GET["scanid"],0,$lastid))
				{
					$progress=$progress."Cancelando la tarea<br>";
					$progress=$progress."Cerrando coneccion con ".$row['direccion_ip']."<br>Tarea finalizada.<br>";
					SqlEx("update ftps set progress='".$progress."' where id=".$_GET["scanid"]);
					SqlEx("update tasks set progreso='".$progress."' where id=".$lastid);
					ftp_close($connection_id);	
					SqlEx("update ftps set status='Parcialmente indizado' where id=".$_GET["scanid"]." ");	
					SqlEx("update tasks set estado='Interrumpida', progreso='Terminada satisfactoriamente' where id=".$lastid);				
				}
				else
				{
					$progress=$progress."Cerrando coneccion con ".$row['direccion_ip']."<br>Tarea finalizada.<br>";
					SqlEx("update ftps set progress='".$progress."' where id=".$_GET["scanid"]);
					SqlEx("update tasks set progreso='".$progress."' where id=".$lastid);
					ftp_close($connection_id);	
					SqlEx("update ftps set status='Indizado' where id=".$_GET["scanid"]." ");	
					SqlEx("update tasks set estado='Finalizada', progreso='Terminada satisfactoriamente' where id=".$lastid);
				}

			}
			if($err==false)			
				$arr = array('result' => 'ok');	
			else	
				$arr = array('result' => 'bad','error'=>$error);	
			//SqlEx("update global set value='iddle' where name='status'");	
			echo json_encode($arr);		
				
			//SqlEx("delete from tasks where idftprelacionado=".$_GET["scanid"]);			
			//SqlEx("update tasks set estado='Finalizada', progreso='...' where id=".$lastid);
		}
	}
	
	function sql($query)
	{
		if(!SqlEx($query))
			exit();
	}
	
	function IndexFtpDirectory($progress,$coneccion,$directorio,$idftp,$nestinglevel,$idtarea)
	{	
		if(substr($directorio,-1)==".")
			return true;
		SqlEx("insert into vieew (value) values ('".$directorio."')");
		$val=GetSingleValue('abortar','tasks',$idtarea);
		if($val==-1)
			raiseerror('no se obtuvo el estado');
		else
			if($val==1)
			return false;
			
		$contents = ftp_rawlist($coneccion, $directorio);
		SqlEx("update ftps set progress='".$progress.addslashes($directorio)."' where id=".$_GET["scanid"]);
		SqlEx("update tasks set progreso='".$progress.addslashes($directorio)."' where id=".$idtarea);
		for($j=0;$j<count($contents);$j++)
		{
			$datatoprocess=MyTrim($contents[$j]);
			
			$data=explode(" ",$datatoprocess,9);
			//SqlEx("insert into vieew (value) values ('".count($data)."')");
			if(substr($data[0],0,1)=="d")
			{
				if(!IndexFtpDirectory($progress,$coneccion,$directorio.'/'.$data[8],$idftp,$nestinglevel+1,$idtarea))
					return false;
			}
			else
			{	
				if(count($data)!=9)
				break;
				$lista="<br>Permisos: ".$data[0].", Numero: ".$data[1].", Tamano: ".$data[4].", Fecha: ".$data[5]."/".$data[6].", Nombre: ".$data[8];
				
				SqlEx("update ftps set progress='".$progress.addslashes($directorio)."<br>Archivo: ".addslashes($lista)."' where id=".$_GET["scanid"]);
				SqlEx("update tasks set progreso='".$progress.addslashes($directorio)."<br>Archivo: ".addslashes($lista)."' where id=".$idtarea);
				//echo("insert into ftptree (nombre,fecha,tamano,profundidad,directorio,idftp,path) values "."('".$data[8]."','".$data[5]."/".$data[6]."','".$data[4]."','".$nestinglevel."',0,'".$idftp."','".$directorio."')");
				mysql_query("insert into ftptree (nombre,fecha,tamanho,profundidad,directorio,idftp,path) values ".
				"('".$data[8]."','".$data[5]."/".$data[6]."','".$data[4]."','".$nestinglevel."',0,'".$idftp."','".$directorio."')");
				//mysql_query("update ftps set progress='".$progress.$contents[$j].$lista."' where id=".$_GET["scanid"]);
				/*echo("Permisos: ".$data[0].".<br>");
				echo("numero: ".$data[1].".<br>");
				echo("Usuario: ".$data[2].".<br>");
				echo("Propietario: ".$data[3].".<br>");
				echo("Tamaño: ".$data[4].".<br>");
				echo("Mes: ".$data[5].".<br>");
				echo("Dia: ".$data[6].".<br>");
				echo("Hora: ".$data[7].".<br>");
				echo("Nombre: ".$data[8].".<br>");*/
				//$result=mysql_query("insert into ftps (nombre,fecha,tamanho,atributos) values (".data[8].",".data[7].",".data[4].",".data[0].")");
			}
		}
		return true;
	}
?>
