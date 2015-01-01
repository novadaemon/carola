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
function ShowDeleteFTPDialog(id)//Muestra el dialogo de eliminar un FTP con id = al parametro id
{
	$('#deleteform').modal('show');
	$('#form_id').val(id);
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
					id:id,
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
					statustd:"<td id=\"row"+$('#actionid').prop('value')+"statustd\">"+$("#row"+$('#actionid').prop('value')+"statustd").html()+"</td>",
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
					remake:rehacer,
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
	$.getJSON( "scripts/php/ajaxftpedit.php",{action:'dismisstask',idtask:id,},function(json) 
			{if(json.result=='ok')
				$('#progressinfopanel'+id).remove();});	
}

function CancelTask(id)//Cancela una tarea
{
	$.getJSON( "scripts/php/ajaxftpedit.php",{action:'canceltask',idtask:id,});	
}

function CheckServerActivity()//Chequea cuantas tareas hay activas en el servidor.
{

//row".$row['id']."statustd;
$.getJSON( "scripts/php/getstatus.php",{action:'getserverstatus',},
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
								$('#row'+idftp+'statustd').html('<img id="indexingajax" src="../img/loading1.gif"> Indizando... (<a href="#" onclick="CancelTask('+id+')">Cancelar</a>)');
							}
							$('#progressinfopanel'+id+'title').html("<b>Estado de la tarea \"<i>"+$(json).prop("task"+i+"descripcion")+"\":</i></b> "+$(json).prop("task"+i+"estado"));
							$('#progressinfopanel'+id+'text').html("<b>Progreso:</b><br> "+$(json).prop("task"+i+"progreso"));
						}
			}
		);
		setTimeout("CheckServerActivity()",5000);
}

$(function(){

	//Setear user y pass cuando se selecciona la opción 'anonimo'
$("input[name=anonimo]").click(function(){
	if($(this).is(":checked")){
		$("#form_usuario").val('anonymous');
		$("#form_usuario").attr('readonly', true);
		$("#form_pass").val('anonymous@ftpindexer.cu');
		$("#form_pass").attr('readonly', true);
	}else{
		$("#form_usuario").val('');
		$("#form_usuario").attr('readonly' , false);
		$("#form_pass").val('');
		$("#form_pass").attr('readonly', false);
	}
})
})

