<?php
	
	
	include 'config.php';//inclusion del archivo de configuracion con los datos de la coneccion a la bd	
	
	if($_POST)//si la pagina es cargada usando el metodo POST	
	{
		if($_POST['action']=='login')//si se ha remitido la accion login se estableceran las cookies en el cliente con los datos de autenticacion
		{
			setcookie("user", $_POST['user'], time()+3600); /*Cookie que contiene el nombre de usuario*/

			setcookie("pass", $_POST['pass'], time()+3600); //Cookie que contiene la contrasenha
			header("Location: ftp.php");//Redirecciona a esta misma pagina para que se cargue utilizando las cookies enviadas
			
			//todo Por hacer: Hay que implementar un mejor sistema de autentificacion porque este esta de palo
			//y las cookies se guardan en texto plano, seria bueno enviar un id de sesion
			// y garantizar el acceso por IP para aumentar la seguridad.
		}	
	}
	if($_GET)//si la pagina es cargada usando el metodo GET
	{
		if($_GET['action']=='exit')//Si se ha remitido la opcion de salir se procedera a eliminar las cookies del cliente
		{
			setcookie("user", '', time()-3600); //Elimina el user
			setcookie("pass", '', time()-3600); //Elimina el password
			header("Location: ftp.php");//Redirecciona a esta misma pagina para que se cargue sin cookies y salga el dialogo de autentificacion
		}
	}
	//A continuacion se imprime la cabecera HTML que se utilizara en cualquier sircunstancia para la salida
?>

<html>
<head>
<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/docs.css" rel="stylesheet">
<?php
	//El siguiente codigo intenta conectarse al servidor utilizando los datos de config.php
	$coneccion=mysql_connect($server,$user,$password) or die //Si no se ha podido emite el siguiente mensaje de error
	("No se ha podido conectar con el servidor de datos, intente acceder mas tarde y si el problema 
	persiste contacte un administrador");
	$db=mysql_select_db("ftpindexer") or die //Intenta conectarse a la BD, si no se puede saca el siguiente error
	("No se ha podido conectar con la base de datos, intente acceder mas tarde y si el problema persiste 
	contacte un administrador");
	//La variable $Error guarda un valor que sera mostrado por esta misma pagina en la linea 50. En otras lineas se modifica sircunstancialmente su valor para mostrar cualquier error que surga en la coneccion y autentificacion.
	$Error='<div class="alert alert-info"  style="width:500px">Escriba sus datos de identificacion</div>';
	
	//La siguiente disyuntiva chequea la existencia de las cookies user y pass
	//de no existir alguna de las dos muestra el dialogo de autentificacion y ejecuta la instruccion de la linea 71 que 
	//envia el preprocesador casi al final del documento para cerrar el html en la linea 618.
	if(!isset($_COOKIE["user"]) or !isset($_COOKIE["pass"])) 
	{	
	//Cualquier salto a la siguiente etiqueta mostrara el dialogo de autentificacion y cerrara el documento saltando a la linea 617.
	// a esta etiqueta se hace referencia en las lineas 88 y 94.
		principio:		
	//El siguiente codigo HTML contiene las cabeceras y el dialogo de autentificacion. Si las cookies
	//estan presentes y el usuario se ha autentificado correctamente esta parte no se mostrara.
	//Esto se chequea en la linea 46, y se ejecuta indirectamente en las lineas 88  y 94.
?>
</head>
<body>
<div class="container">
<?php echo($Error."<br>"); ?>
<form action="ftp.php" method="post" role="form" class="highlight" style="width:500px">
  <div class="form-group">
    <label for="usuario1">Usuario</label>
    <input type="text" class="form-control" id="usuario1" name="user" placeholder="Escriba su nombre de usuario">
  </div>
  <div class="form-group">
    <label for="pass1">Contrasena</label>
    <input type="password" class="form-control" id="pass1" name="pass" placeholder="Contrasena">
  </div>
  <div class="checkbox">
    <label>
      <input type="checkbox"> Recordarme
    </label>
  </div>
  <button type="submit" class="btn btn-default" name="action" value="login">Aceptar</button>
</form>
</div>
<?php			
		goto tag;//Envia a la linea 617 y cierra el documento HTML.
	}
	else//El siguiente codigo se ejecuta si estan presentes las cookies user y pass.
	{
		$clave = array_search($_COOKIE["user"], $usuarios); //Se buscara el nombre de usuario contenido en la cookie dentro del arreglo $usuarios que esta definido en config.php
		if($clave!==false)//Si se ecuentra un usuario con el nombre contenido en la cookie:
		{		
			if($contrasenas[$clave]!=$_COOKIE["pass"])//Revisara el valor del arreglo $contrasenhas contenido en config.php que esta en el indice contenido en $clave.  y si no coincide con el valor contenido en la cookie guarda el mensaje de error de contrasenha invalida en la variable $Error y envia a la linea 51 para que se muestre el dialogo de autentificacion nuevamente.
			{	
			$Error='<div class="alert alert-danger"  style="width:500px">La contrasena escrita es incorrecta</div>';
			goto principio;
			}//si la contrasenha coincide el codigo continuara ejecutandose en la linea 100
		}
		else//Si no se encuentrra el usuario en la lista remite a la linea 51 y muestra el dialogo de autentificacion con el mensaje de usuario no encontrado.
		{
			$Error='<div class="alert alert-danger"  style="width:500px">Usuario desconocido</div>';
			goto principio;
		}
	}	//EL SIGUIENTE CODIGO SE PROCESA SI Y SOLO SI LAS COOKIES QUE TIENE EL NAVEGADOR CONTIENEN DATOS DE AUTENTIFICACION PREVIAMENTE VALIDADOS.
?>
<script>
var alertcount=0;
var indexing=false;
function Alertar(tipo,titulo,texto)// Esta funcion muestra un mensaje de alerta o informacion insertandolo codigo HTML dinamicamente en el documento.
{
if(alertcount>0)
setInterval("CerrarAlerta(\"#infoalert"+(alertcount-1)+"\")",3000);
var al=$('#contenido').append("<div class=\"alert "+tipo+" fade in alert-dismissable\" id=\"infoalert"+alertcount+"\">"+
"<button class=\"close\" aria-hidden=\"true\" data-dismiss=\"alert\" type=\"button\">&times;</button>"+
    "<h4 id=\"infoalerttitle\">"+titulo+"</h4>"+
	"<p id=\"infoalerttext\">"+texto+"</p>"+
"</div>");
//alert(parseInt($('#infoalert'+alertcount).offset().top));
window.scroll(0,parseInt($('#infoalert'+alertcount).offset().top));

alertcount++;
}

function CerrarAlerta(alerta)//Esta funcion cierra una alerta con el id referido en el parametro alerta.
{
$(alerta).alert('close');
}
function ShowInsertFTPDialog()//Muestra el dialogo de insertar un FTP
{
	$('#myModalLabel').text("Agregar un nuevo FTP a la lista del indizador.");
	$('#insertform').modal('show');
	$('#action').prop('value','insert');
}
function ShowDeleteFTPDialog(id)//Muestra el dialogo de eliminar un FTP con id= al parametro id
{
	$('#deleteform').modal('show');
	$('#ftptodelete').prop('value',id);
}
function ShowEditFTPDialog(id)//Muestra el dialogo para modificar un ftp con id= al parametro id y lo rellena obteniendo los datos del servidor para el ftp seleccionado.
{
	$('#myModalLabel').text("Editar un FTP de la lista del indizador con id: "+id);
	$('#insertform').modal('show');
	$('#action').prop('value','edit');
	$('#actionid').prop('value',id);
	$.ajax(
	{   
		url : "scripts/php/ajaxftpedit.php",   
		data : {    action : "getrowbyid",
					id:id
				},
		type : "GET",  
		dataType : "json",     
		success : function( json ) //Si se obtienen los datos satisfactoriamente rellena los campos del dialogo con los datos del ftp que se va a editar.
					{  
						if(json.result=="ok")						
							{
								$("#descripcion").prop("disabled", false);
								$("#dirip").prop("disabled", false);
								$("#user").prop("disabled", false);
								$("#pass").prop("disabled", false);
								$("#activeforindexing").prop("disabled", false);
								$("#descripcion").prop("value", json.descripcion);
								$("#dirip").prop("value", json.direccion);
								$("#user").prop("value", json.user);
								$("#pass").prop("value", json.pass);
								if(json.activo==1)
								$('#activeforindexing').prop('checked',true);
								else
								$('#activeforindexing').prop('checked',false);
							}
						else
							{
								Alertar("alert-warning","Error!","No se han podido obtener los datos del FTP de la base de datos.<br>El texto del error devuelto es: <br>"+json.error);			
								$('#ajaxwait').hide();
								$('#ajaxinsertbutton1').show();
								$('#ajaxinsertbutton2').show();
								$('#insertform').modal('hide');
								$("#descripcion").prop("disabled", false);
								$("#dirip").prop("disabled", false);
								$("#user").prop("disabled", false);
								$("#pass").prop("disabled", false);
								$("#activeforindexing").prop("disabled", false);
							}
					},   
		error : function( xhr, status ) 
					{    
						Alertar("alert-danger","Error!","No se ha podido completar la accion solicitada. Los datos tecnicos del error son los siguientes:<br>xhr="+xhr+"<br>status="+status);							
						$('#ajaxwait').hide();
						$('#ajaxinsertbutton1').show();
						$('#ajaxinsertbutton2').show();
						$('#insertform').modal('hide');
						$("#descripcion").prop("disabled", false);
						$("#dirip").prop("disabled", false);
						$("#user").prop("disabled", false);
						$("#pass").prop("disabled", false);
						$("#activeforindexing").prop("disabled", false);
					},   
		complete : function( xhr, status ) 
					{ 						
					} 
	});
}

function DeleteFtp()//Elimina un ftp con el id contenido en el input oculto del dialogo DeleteFtp si el usuario dio click en eceptar.
{
$('#ajaxwait2').show();
$('#ajaxdeletebutton1').hide();
$('#ajaxdeletebutton2').hide();
$.ajax(
	{   
		url : "scripts/php/ajaxftpedit.php",   
		data : {    action : "delete",
					ftptodelete:$('#ftptodelete').prop('value')
				},
		type : "POST",  
		dataType : "json",     
		success : function( json ) 
					{  
						if(json.result=="ok")						
							{								
								Alertar("alert-success","Accion finalizada satisfactorimente","Se pudo eliminar correctamente el FTP de la lista de sitios a indizar.");							
								$("#rowid"+$("#ftptodelete").prop("value")).remove();
							}
						else
							{
								Alertar("alert-warning","Error!","No se ha podido eliminar el FTP de la base de datos.<br>El texto del error devuelto es: <br>"+json.error);			
								$("#ftptodelete").prop("value", "");
							}
					},   
		error : function( xhr, status ) 
					{    
						Alertar("alert-danger","Error!","No se ha podido completar la accion solicitada. Los datos tecnicos del error son los siguientes:<br>xhr="+xhr+"<br>status="+status);							
						$("#ftptodelete").prop("value", "");
					},   
		complete : function( xhr, status ) 
					{ 
						$('#ajaxwait2').hide();
						$('#ajaxdeletebutton1').show();
						$('#ajaxdeletebutton2').show();
						$('#deleteform').modal('hide');	
						$("#ftptodelete").prop("value", "");						
					} 
	});
}

function AddOrEditFtp()//Ejecuta la accion de agregar o editar un ftp al servidor de datos.
{
$('#ajaxwait').show('fast');
$('#ajaxinsertbutton1').hide();
$('#ajaxinsertbutton2').hide();
$("#descripcion").prop("disabled", true);
$("#dirip").prop("disabled", true);
$("#user").prop("disabled", true);
$("#pass").prop("disabled", true);
$("#activeforindexing").prop("disabled", true);
$.ajax(
	{   
		url : "scripts/php/ajaxftpedit.php",   
		data : {    action : $('#action').prop('value'),
					actionid : $('#actionid').prop('value'),
					descripcion:$('#descripcion').prop('value'),
					direccion:$('#dirip').prop('value'),
					activo:$('#activeforindexing').prop('checked'),
					user:$('#user').prop('value'),
					pass:$('#pass').prop('value'),
					statustd:"<td id=\"row"+$('#actionid').prop('value')+"statustd\">"+$("#row"+$('#actionid').prop('value')+"statustd").html()+"</td>"
					}, 
		type : "POST",  
		dataType : "json",     
		success : function( json ) 
					{   // alert("success"); 
						if(json.result=="ok")						
							{								
								if($('#action').prop('value')=='insert')
								{
									$('#ftplist').append(json.newrow);
									Alertar("alert-success","Accion finalizada satisfactorimente","Se pudo insertar correctamente un nuevo FTP en la lista de sitios a indizar."+json.aditionalinformation);							
									$('#emptyrow').hide();
								}
								else if($('#action').prop('value')=='edit')
								{
									$('#rowid'+$('#actionid').prop('value')).html(json.editedrow);
									Alertar("alert-success","Accion finalizada satisfactorimente","Se pudo editar correctamente el FTP con id"+$('#actionid').prop('value')+" de la lista de sitios a indizar."+json.aditionalinformation);							
								}
							}
						else
							Alertar("alert-warning","Error!","No se ha podido insertar el nuevo FTP en la base de datos. Revise los datos introducidos e intentelo de nuevo. El texto del error devuelto es: <br>"+json.error);			
					},   
		error : function( xhr, status ) 
					{    
						Alertar("alert-danger","Error!","No se ha podido completar la accion solicitada. Los datos tecnicos del error son los siguientes:<br>xhr="+xhr+"<br>status="+status);							
					},   
		complete : function( xhr, status ) 
					{ 
						$('#ajaxwait').hide();
						$('#ajaxinsertbutton1').show();
						$('#ajaxinsertbutton2').show();
						$('#insertform').modal('hide');
						$("#descripcion").prop("disabled", false);
						$("#dirip").prop("disabled", false);
						$("#user").prop("disabled", false);
						$("#pass").prop("disabled", false);
						$("#activeforindexing").prop("disabled", false);
					} 
	});
}

/*function GetIndexingInfoAndStatus(id,updateintable)
{
$.ajax(
	{   
		url : "scripts/php/getstatus.php",   
		data : {    action : "getftpstatus",
					ftpid:id,
				},
		type : "GET",  
		dataType : "json",     
		success : function( json ) 
					{  
						if(json.result=="ok")						
							{		
								if(updateintable==false)		
								{
									$('#infoalerttext').html("<b>Estado:</b>"+json.status+"</br><b>Progreso:</b><br>"+json.progress);
									if(indexing==true)
									setTimeout("GetIndexingInfoAndStatus("+id+",false)",3000);
								}
								else
								{
									if(json.status=="Indizado")
									$("#row"+id+"statustd").html("Indizado (<a href='#' onclick='IndizarFTP("+id+",true)'>Rehacer</a>)");
									if(json.status=="No indizado")
									$("#row"+id+"statustd").html("No indizado (<a href='#' onclick='IndizarFTP("+id+",false)'>Indizar</a>)");
								}
							}
						else
							{
								Alertar("alert-warning","Error!","No se han podido obtener los datos requeridos para la actualizacion del estado<br>El texto del error devuelto es: <br>"+json.error);			
							}
					},   
		error : function( xhr, status ) 
					{    
						Alertar("alert-danger","Error!","No se ha podido completar la accion solicitada. Los datos tecnicos del error son los siguientes:<br>xhr="+xhr+"<br>status="+status);							
						$("#ftptodelete").prop("value", "");
					},   
		complete : function( xhr, status ) 
					{ 
				
					} 
	});

}*/
function IndizarFTP(id,rehacer)//Envia una peticion al servidor para indizar un ftp con id= al parametro id
{
//$('#progressalert'+id+'closebutton').hide();
//$('#progressalert').show();
//indexing=true;
//$('#indexingajax').show();
//$('#infoalerttext').html("<b>Estado: </b>Esperando respuesta...</br><b>Progreso: </b>Esperando respuesta...");
//setTimeout("GetIndexingInfoAndStatus("+id+",false)",4000);
$.ajax(
	{   
		url : "scripts/php/scan.php",   
		data : {    action : "scansingle",
					scanid:id,
					remake:rehacer
				},
		type : "GET",  
		dataType : "json",     
		success : function( json ) 
					{  
						if(json.result=="ok")						
							{								
								Alertar("alert-success","Accion finalizada satisfactorimente","Indizacion completada");							
								//$('#indexingajax').hide();
								//indexing=false;
							}
						else
							{
								Alertar("alert-warning","Error!","No se ha podido realizar la indizacion del ftp seleccionado<br>El texto del error devuelto es: <br>"+json.error);			
								//$('#indexingajax').hide();
								//indexing=false;
							}
					},   
		error : function( xhr, status ) 
					{    
						Alertar("alert-danger","Error!","No se ha podido completar la accion solicitada. Los datos tecnicos del error son los siguientes:<br>xhr="+xhr+"<br>status="+status);							
						//$("#ftptodelete").prop("value", "");
					},   
		complete : function( xhr, status ) 
					{ 
						//$('#progressalertclosebutton').show();
						//GetIndexingInfoAndStatus(id,true)
					} 
	});
}

function AddTaskAlert(id)//Agrega una notificacion de estado de alguna tarea para que el usuario la vea.
{//style="display:none"
	$('#contenido').append('<div class="alert alert-info fade in alert-dismissable" id="progressinfopanel'+id+'">'+
	'<button class="close" aria-hidden="true" type="button" style="display:none" onclick="DismissTask('+id+');" id="progressinfopanel'+id+'closebutton">&times;</button>'+
	'<h4 id="progressinfopanel'+id+'title"><b>Estado de la tarea:</b> Esperando respuesta...</h4>'+
	'<p id="progressinfopanel'+id+'text"><b>Progreso:</b> Esperando respuesta...</p>'+
	'</div>');
	//setTimeout("CheckTaskStatus("+id+")",3000);
}

function CheckTaskStatus(id)//Chequea el estado de las tareas activas. Esta funcion se ejecuta cada x segundos para mantener actualizada la vista.
{
$.getJSON( "scripts/php/getstatus.php",{action:'gettaskstatus',taskid:id},
			function(json) 
			{
				if(json.result=='ok')
				{
					$('#infoalerttitle'+id).text(json.status);
					$('#infoalerttext'+id).text(json.progress);
				}
			}
		);
		setTimeout("CheckTaskStatus("+id+")",3000);

}

function DismissTask(id)//Cierra una notificacion de una tarea.
{
	$.getJSON( "scripts/php/ajaxftpedit.php",{action:'dismisstask',idtask:id},function(json)
			{if(json.result=='ok')
				$('#progressinfopanel'+id).remove();});	
}

function CancelTask(id)//Cancela una tarea
{
	$.getJSON( "scripts/php/ajaxftpedit.php",{action:'canceltask',idtask:id});
}

function CheckServerActivity()//Chequea cuantas tareas hay activas en el servidor.
{

//row".$row['id']."statustd;
$.getJSON( "scripts/php/getstatus.php",{action:'getserverstatus'},
			function(json) 
			{
				if(json.result=='ok')					
						for(var i=0;i<json.activetasks;i++)
						{
							var id=$(json).prop("task"+i);
							var idftp=$(json).prop("task"+i+"ftprelacionado");
							if($('#progressinfopanel'+id).length==0) 
								AddTaskAlert(id);
							if($(json).prop("task"+i+"estado")=="Finalizada")
							{
								$('#row'+idftp+'statustd').html('Indizado (<a href=\'#\' onclick=\'IndizarFTP("'+idftp+'",true)\'>Rehacer</a>)');
								$('#progressinfopanel'+id).removeClass();
								$('#progressinfopanel'+id).addClass("alert alert-success fade in alert-dismissable");
								$('#progressinfopanel'+id+'closebutton').show();	
							}
							else if($(json).prop("task"+i+"estado")=="Finalizada con errores")
							{
								$('#row'+idftp+'statustd').html('Error al indizar (<a href=\'#\' onclick=\'IndizarFTP("'+idftp+'",true)\'>Rehacer</a>)');
								$('#progressinfopanel'+id).removeClass();
								$('#progressinfopanel'+id).addClass("alert alert-danger fade in alert-dismissable");
								$('#progressinfopanel'+id+'closebutton').show();	
							}
							else if($(json).prop("task"+i+"estado")=="Interrumpida")
							{
								$('#row'+idftp+'statustd').html('Parcialmente indizado (<a href=\'#\' onclick=\'IndizarFTP("'+idftp+'",true)\'>Rehacer</a>)');
								$('#progressinfopanel'+id).removeClass();
								$('#progressinfopanel'+id).addClass("alert alert-warning fade in alert-dismissable");
								$('#progressinfopanel'+id+'closebutton').show();	
							}
							else
							{
								$('#row'+idftp+'statustd').html('<img id="indexingajax" src="media/gif/loading1.gif"> Indizando... (<a href="#" onclick="CancelTask('+id+')">Cancelar</a>)');
							}
							$('#progressinfopanel'+id+'title').html("<b>Estado de la tarea \"<i>"+$(json).prop("task"+i+"descripcion")+"\":</i></b> "+$(json).prop("task"+i+"estado"));
							$('#progressinfopanel'+id+'text').html("<b>Progreso:</b><br> "+$(json).prop("task"+i+"progreso"));
						}
			}
		);
		setTimeout("CheckServerActivity()",5000);
}
</script>
<!--</head>-->
<body>
 <script src="scripts/js/jquery.js"></script>
 <script src="scripts/js/modal.js"></script>
 <script src="scripts/js/bootstrap.js"></script>
 <script src="scripts/js/alert.js"></script>
 <div class="container bs-docs-container" id="contenido">
<div class="bs-example bs-navbar-bottom-example">
<nav class="navbar navbar-default navbar-fixed-bottom" role="navigation">
    <div class="navbar-header">
        <a class="navbar-brand" href="#" onclick="ShowInsertFTPDialog()" >
            Insertar un nuevo FTP
        </a>
		<a class="navbar-brand" href="ftp.php?action=exit">
            Salir del panel administrativo
        </a>
    </div>
    <div class="collapse navbar-collapse navbar-ex7-collapse">
        <ul class="nav navbar-nav"></ul>
    </div>
</nav></div>
<div class="highlight" style="background-color:#FFFFFF">
<div class="modal fade" id="insertform" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" id="insertdialogcontent">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Agregar un nuevo FTP a la lista del indizador.</h4>
      </div>
		<div class="form-horizontal" style="margin-top:10px">
		<input type="hidden" id="action">
		<input type="hidden" id="actionid">
			<div class="form-group">
				<label class="col-sm-3 control-label" for="descripcion">
					Descripcion
				</label>
				<div class="col-sm-8">
					<input id="descripcion" class="form-control" type="text" placeholder="Descripcion del FTP que desea agregar">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label" for="dirip">
					Direccion
				</label>
				<div class="col-sm-4">
					<input id="dirip" class="form-control" type="text" placeholder="Direccion IP">
				</div>
				<div class="col-sm-4">
					<div class="checkbox"><label><input type="checkbox" id="activeforindexing">Activo para busquedas </label></div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label" for="user">
					Usuario
				</label>
				<div class="col-sm-3">
					<input id="user" class="form-control" type="text" placeholder="Usuario">
				</div>
				<label class="col-sm-2 control-label" for="pass">
					Contrasena
				</label>
				<div class="col-sm-3">
					<input id="pass" class="form-control" type="password" placeholder="Contrasena">
				</div>
			</div>
		</div>
      <div class="modal-footer">
		<div class="col-sm-6" id="ajaxwait" style="display:none;">
		<img src="media/gif/loading1.gif"> Espere un momento por favor...
		</div>	  
		<button id="ajaxinsertbutton1" type="button" class="btn btn-primary" onclick="AddOrEditFtp()">Aceptar</button>
        <button id="ajaxinsertbutton2" type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>        
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div class="modal fade" id="deleteform" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" id="insertdialogcontent">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel1">Eliminar FTP.</h4>
      </div>
		<div class="form-horizontal" style="margin-top:10px">
			<div class="form-group">
			<input type="hidden" id="ftptodelete">
			<div class="col-sm-10" style="margin-left:20px;">
				Si elimina este FTP tambien se eliminaran todos los indices relacionados y se cancelaran todas las tareas activas que hagan referencia a el. Si el FTP cambio de direccion utilice la funcion Editar.
				<br><br>
				<b>De verdad desea eliminar este FTP?</b>
				</div>
			</div>
		</div>
      <div class="modal-footer">
		<div class="col-sm-6" id="ajaxwait2" style="display:none;">
		<img src="media/gif/loading1.gif"> Espere un momento por favor...
		</div>	  
		<button id="ajaxdeletebutton1" type="button" class="btn btn-danger" onclick="DeleteFtp()">Si quiero</button>
        <button id="ajaxdeletebutton2" type="button" class="btn btn-success" data-dismiss="modal">NOOOOOOOOOOO!!!</button>        
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<?php
	$result=mysql_query("select * from ftps");
	$count=mysql_num_rows($result);
	echo("<table class=\"table table-striped\"><thead>");
	echo("<tr><td width=\"40px\"></td><td width=\"40px\">Activo</td><td  width=\"250px\">Descripcion</td><td>Direccion</td><td>Usuario</td><td>Estado</td></tr></thead><tbody id=\"ftplist\">");
	if($count==0)
	echo("<tr id='emptyrow'><td colspan=\"6\">No hay ftps para mostrar. Inserte un nuevo ftp para comenzar a trabajar.</td></tr>");
	else
	for($i=0;$i<$count;$i++)
	{
		$row=mysql_fetch_array($result);
		if($row['activo']=='1')
		$activo="checked";
		else
		$activo="";
		$extrastatus="";
		if($row['status']=="No indizado")
		$extrastatus=" (<a href='#' onclick='IndizarFTP(".$row['id'].",false)'>Indizar</a>)";	
		else if($row['status']=="Indizado" || $row['status']=="Parcialmente indizado" || $row['status']=="Error al indizar")
		$extrastatus=" (<a href='#' onclick='IndizarFTP(".$row['id'].",true)'>Rehacer</a>)";			
		echo("<tr id='rowid".$row['id']."'><td><a href='#' onclick='ShowEditFTPDialog(".$row['id'].")'><img src=\"media/png/edit.png\"></a>&nbsp;&nbsp;<a href='#' onclick='ShowDeleteFTPDialog(".$row['id'].")'><img src=\"media/png/delete.png\"></a></td><td class=\"text-center\"><input type=\"checkbox\" id=\"activeforindexing\" ".$activo."></td><td>".$row['descripcion']."</td><td>".$row['direccion_ip']."</td><td>".$row['user']."</td><td id='row".$row['id']."statustd'>".$row['status'].$extrastatus."</td></tr>");	
	}
	echo("</tbody></table>");
?>
</div>
<!--<div class="alert alert-info fade in alert-dismissable" id="progressalert" style="display:none">
<button class="close" aria-hidden="true" type="button" style="display:none" onclick="$('#progressalert').hide();" id="progressalertclosebutton">&times;</button>
<h4 id="infoalerttitle"><img id="indexingajax" src="media/gif/loading1.gif" style="display:none">Progreso de la indizacion</h4>
<p id="infoalerttext">No hay informacion disponible.</p>
</div> -->
</div>

<script>CheckServerActivity();</script>
<?php
tag:
echo("</body></html>");
?>