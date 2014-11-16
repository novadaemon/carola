<?php
include '..\..\config.php';
include '..\..\include\general.php';
$coneccion=mysql_connect($server,$user,$password);
$db=mysql_select_db("ftpindexer");
if($_GET)
{
	if($_GET["action"]=="getftpstatus")
	{
		$result=mysql_query("select * from ftps where id=".$_GET["ftpid"]);
		$row=mysql_fetch_array($result);
		$arr = array('result' => 'ok','status'=>$row["status"],'progress'=>$row["progress"]);	
		echo json_encode($arr);	
	}
	else if($_GET["action"]=="getserverstatus")
	{
		$result=mysql_query("select value from global where name='status'");
		$arr = array('result' => 'ok','status'=>'iddle');				
		if(mysql_num_rows($result)!=0)
		{

			$rsss=mysql_fetch_array($result);
			$stat=$rsss['value'];
			$arr = array('result' => 'ok','status'=>$stat);	
			$result=mysql_query("select * from tasks");		
			$cantidad=mysql_num_rows($result);	
			$arr['activetasks']=$cantidad;				
			for($i=0;$i<$cantidad;$i++)
			{	
				$row=mysql_fetch_array($result);
				$arr['task'.$i]=$row['id'];
				$arr['task'.$i.'ftprelacionado']=$row['idftprelacionado'];
				$arr['task'.$i.'descripcion']=$row['descripcion'];
				$arr['task'.$i.'estado']=$row['Estado'];
				$arr['task'.$i.'progreso']=$row['Progreso'];
			}			
		}
		echo json_encode($arr);	
	}
	else if($_GET["action"]=="gettaskstatus")
	{
		$result=mysql_query("select * from tasks where id=".$_GET["taskid"]);
		$arr = array('result' => 'bad');	
		if($result)		
		if(mysql_num_rows($result)!=0)
		{
			$row=mysql_fetch_array($result);
			$arr = array('result' => 'ok','status'=>$row['Estado'],'progress'=>$row['Progreso']);		
		}
		echo json_encode($arr);	
	}
}
?>