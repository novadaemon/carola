<?php
function SqlEx($query,$deveretornar=false,$errortext="",$appendtechnicaldata=true)
	{
		$result=mysql_query($query);
		if(!$result)
		{
			if($appendtechnicaldata)
				$errortext=$errortext."Error 1-Consulta: ".$query."<br>Datos tecnicos del error: <b>".mysql_error()."</b>";
			raiseerror($errortext);
			return false;
		}
		if($deveretornar==true)
		{
			if(mysql_num_rows($result))
				if(mysql_num_rows($result)==0)
				{
					if($appendtechnicaldata)
						$error_text=$error_text."Error 1-Consulta: ".$query."<br>Datos tecnicos del error: <b>".mysql_error()."</b>";
					raiseerror($error_text);
					return false;
				}
		}
		return true;
	}
	
	function raiseerror($error_text)
	{
		$arr = array('result' => 'bad','error'=>$error_text);	
		echo json_encode($arr);	
	}
	
	function MyTrim($str)
	{
		$tempvar="";
		$size=strlen($str);
		$cursor=-1;
		$preceding_is_space=false;
		tag:
		if($cursor>=$size)
			goto end;
		$cursor++;
		$currentchar=substr($str,$cursor,1);
		if($currentchar!=" ")
		{
			$tempvar=$tempvar.$currentchar;
			$preceding_is_space=false;
			goto tag;
		}
		else
		{
			if($preceding_is_space==true)
				goto tag;	
			$tempvar=$tempvar.$currentchar;
			$preceding_is_space=true;
			goto tag;
		}
		end:
		return $tempvar;
	}
	
	function GetSingleValue($fieldname,$tablename,$index)
	{	
		$result=mysql_query('select '.$fieldname.' from '.$tablename.' where id='.$index);
		if(!$result)	
			return -1;
		if(mysql_num_rows($result))
			if(mysql_num_rows($result)==1)
				{
					$arra=mysql_fetch_array($result);
					return $arra[$fieldname];
				}
			else
				return -1;
	}
?>