<?php
//Ejecuta una consulta sql y si debe devolver un resultset pero la respuesta es vacia devuelve un error, $errortext se pasa como parametro si se quiere que la funcion lo agregue como prefijo a los mensajes de error generados por la accion de consulta, si $appendtechnicaldata es falso no se agregaran los detalles del error generado por la consulta
function SqlEx($query,$deveretornar=false,$errortext="",$appendtechnicaldata=true)
	{
		$result=mysql_query($query);//Ejecuta la consulta
		if(!$result)//Si no devuelve un resultset valido
		{
			if($appendtechnicaldata)//si se ha especificado en los parametros que se deben agregar los detalles tecnivos del error al mensaje de salida
				$errortext=$errortext."Error 1-Consulta: ".$query."<br>Datos tecnicos del error: <b>".mysql_error()."</b>";//Agrega los detalles tecnicos al mensaje de error predeterminado en $errortext
			raiseerror($errortext);//imprime un json entendible por el documento que realizo la consulta especificado que ha ocurrido un error
			return false;//devuelve falso para que se entienda que la ejecucion de la consulta fracaso
		}
        //En este punto se sobreentiende que $result es true pues fue chequeado en la linea 6
		if($deveretornar==true)//Si fue especificad en los aprametros que la fucnion debe devolver un resultset
		{
			if(mysql_num_rows($result))//Si se ha devuelto un resultset en la consulta
				if(mysql_num_rows($result)==0)//si no hay resultados en el resultset devuelto
				{
					if($appendtechnicaldata)//mismo linea 8
						$error_text=$error_text."Error 1-Consulta: ".$query."<br>Datos tecnicos del error: <b>".mysql_error()."</b>";//Mismo linea 9
					raiseerror($error_text);//mismo linea 10
					return false;//mismo linea 11
				}
		}
		return true;//La consulta se ejecuto satisfactoriamente
	}
	
	function raiseerror($error_text)//imprime un error en formato json interpretable por el documento que yama la funcion
	{
		$arr = array('result' => 'bad','error'=>$error_text);//crea un arreglo con los parametros result=bad y error=al parametro error_text
		echo json_encode($arr);	//codifica el arreglo como json para que sea interpretado por la pagina que yamo la funcion
	}
	
	function MyTrim($str)//algoritmo para eliminar los espacion sin sentido en las lineas devueltas por los comandos ejecutados en un ftp
	{
		$tempvar="";//se crea una variable temporal que almacenara la salida del metodo
		$size=strlen($str);//se almacena la longitud de la cadena $str pasada como parametro a la funcion
		$cursor=-1;//pone el cursor en -1
		$preceding_is_space=false;//bandera que indica si el caracter anteriormente analizado fue un espacio
		tag:
		if($cursor>=$size)//chequea si se alcanzo el final de la cadena
			goto end;//va al final del metodo
		$cursor++;//incrementa el cursor en 1 caracter
		$currentchar=substr($str,$cursor,1);//guarda temporalmente el caracter que se esta analizando en $curentchar
		if($currentchar!=" ")//si es un espacio
		{
			$tempvar=$tempvar.$currentchar;//se agrega el caracter que se esta analizando a la cadena de salida
			$preceding_is_space=false;//se apaga la bandera especificada en la linea 39
			goto tag;//vuelve al inicio del ciclo para analizar el siguiente caracter
		}
		else //si no es un espacio
		{
			if($preceding_is_space==true) //si el caracter anterior es un espacio
				goto tag;	//reinicia el ciclo
			$tempvar=$tempvar.$currentchar;//si no es un espacio (se chequea en la linea 53)
			$preceding_is_space=true;//se enciende la bandera de la linea 39
			goto tag; //reinicia el ciclo
		}
		end:
		return $tempvar;//devuelve la variable temporal con el string trimeado
	}
	
	function GetSingleValue($fieldname,$tablename,$index)//obtiene el valor de una celda con el indice especificado en $index
	{	
		$result=mysql_query('select '.$fieldname.' from '.$tablename.' where id='.$index);//ejecuta la consulta
		if(!$result)	//si no hay resultados
			return -1;//devuelve error
		if(mysql_num_rows($result))//si hay un resultset
			if(mysql_num_rows($result)==1)//si hay un solo resultado
				{
					$arra=mysql_fetch_array($result);//guarda la linea de resultados en $arra
					return $arra[$fieldname];//y devuelve el valor de la columna con nombre $fieldname
				}
			else//si hay mas de un resultado
				return -1;//devuelve error
	}
?>