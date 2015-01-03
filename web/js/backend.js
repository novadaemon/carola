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
function waitUser(n){
    var t=($(document).width()-150)/2;
    var i=$(document).scrollTop() + $(window).height() / 2 - 150;
    $("#divLoading > i").css({"margin-top":i,"margin-left":t});
    n ? $("#divLoading").show() : $("#divLoading").hide();
}
function CerrarAlerta(alerta)//Esta funcion cierra una alerta con el id referido en el parametro alerta.
{
$(alerta).alert('close');
}
function ShowInsertFTPDialog()//Muestra el dialogo de insertar un FTP
{
	$('#myModalLabel').text("Agregar un nuevo FTP.");
	$("#ftp_form").attr('action', insertRoute );
	//Limpiar los campos del formulario
	$("#ftp_form").find("input:not(:last)").val('');
	$("#form_user").removeAttr('readonly');
	$("#form_pass").removeAttr('readonly');

	$('#insertform').modal('show');
}
function ShowDeleteFTPDialog(id)//Muestra el dialogo de eliminar un FTP con id = al parametro id
{
	$('#deleteform').modal('show');
	$('#form_id').val(id);
}
function ShowEditFTPDialog(id)//Muestra el dialogo para modificar un ftp con id= al parametro id y lo rellena obteniendo los datos del servidor para el ftp seleccionado.
{
	$('#myModalLabel').text("Editar FTP");
	$("#ftp_form").attr('action', updateRoute.replace(/__id__/g,id));
	$("#ftp_id").val(id);

	waitUser(true);
	$.ajax(
	{   
		url : getDataRoute,   
		type : "POST",
		data: { 'id': id },  
		success : function( json ) //Si se obtienen los datos satisfactoriamente rellena los campos del dialogo con los datos del ftp que se va a editar.
					{  
						waitUser(false);
						if(json.success == true){
							$("#form_descripcion").val(json.data[0].descripcion);
							$("#form_direccion_ip").val(json.data[0].direccion_ip);
							$("#form_activo").attr('checked', json.data[0].activo);
							$("#form_user").val(json.data[0].user);
							$("#form_pass").val(json.data[0].pass);
							
							$("#form_user").attr('readonly', json.data[0].user == 'anonymous');
							$("#form_pass").attr('readonly', json.data[0].user == 'anonymous');
							$("#form_anonimo").attr('checked', json.data[0].user == 'anonymous');
							
							$('#insertform').modal('show');

						}else{
							Alertar("alert-warning","Error!",json.message);			
						}
					},   
		error : function( xhr, status ) 
					{    
						waitUser(false);
						Alertar("alert-danger","Error!","No se ha podido completar la accion solicitada. Los datos tecnicos del error son los siguientes:<br>xhr="+xhr+"<br>status="+status);							

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
function IndexarFTP(id, ip, obj)//Envia una petición al servidor para indizar un ftp con id= al parametro id
{
$(obj).next("img").show();
$(obj).addClass('disabled');
$(obj).attr('title', 'Indexando...');
$.ajax(
	{   
		url : scanRoute, 
		type: 'POST',  
		data : { 'id' : id },
		success : function( json ) 
					{  
						$(obj).next("img").hide();
						$(obj).removeClass('disabled');
						console.log(json);
						if(json[ip].success == true)				
							{								
								Alertar("alert-success","Acción finalizada satisfactoriamente","Indexación para el ftp "+ ip + " completada.");							
								//$('#indexingajax').hide();
								//indexing=false;
							}
						else
							{
								Alertar("alert-danger","Error!","No se ha podido realizar la indexación para el ftp" + ip + "<br>El texto del error devuelto es: <br>"+json[ip].message);			
								//$('#indexingajax').hide();
								//indexing=false;
							}
					},   
		error : function( xhr, status ) 
					{    
						$(obj).next("img").hide();
						$(obj).removeClass('disabled');
						Alertar("alert-danger","Error!","No se ha podido completar la acción solicitada. Los datos técnicos del error son los siguientes:<br>xhr="+xhr.responseText+"<br>status="+status);							
						//$("#ftptodelete").prop("value", "");
					}  

	});
return false;
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
		$("#form_user").val('anonymous');
		$("#form_user").attr('readonly', true);
		$("#form_pass").val('anonymous@ftpindexer.cu');
		$("#form_pass").attr('readonly', true);
	}else{
		$("#form_user").val('');
		$("#form_user").attr('readonly' , false);
		$("#form_pass").val('');
		$("#form_pass").attr('readonly', false);
	}
})
})

