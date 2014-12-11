<html>
<head>
<meta charset="UTF-8" />
<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/docs.css" rel="stylesheet">
<link href="css/carola_site.css" rel="stylesheet">
</head>
<body>
 <script src="scripts/js/jquery.js"></script>
 <script src="scripts/js/modal.js"></script>
 <script src="scripts/js/bootstrap.js"></script>
 <script src="scripts/js/alert.js"></script>
  <script src="scripts/js/bootstrap-typeahead.js"></script>
 <div id="carola-nav" style="position:fixed;top:0px;">	
 <!-- <img src="newbeta.png" style="position:absolute;display:block;"> -->
      <ul class="nav nav-pills" style="margin-left:40px;">	  
        <li class="dropdown">
          <a id="drop4" role="button" data-toggle="dropdown" href="#">Web Sociales<b class="caret"></b></a>
          <ul id="menu1" class="dropdown-menu" role="menu" aria-labelledby="drop4">
            <li role="presentation"><a role="menuitem" tabindex="-1" href="http://192.168.32.1/netlab/">NetLab</a></li>
            <li role="presentation"><a role="menuitem" tabindex="-1" href="http://192.168.128.1/forum/">Foro SNET</a></li>
            <li role="presentation"><a role="menuitem" tabindex="-1" href="http://192.168.39.1/Social_Habana/index.php/">Social Habana</a></li>
            <li role="presentation"><a role="menuitem" tabindex="-1" href="http://192.168.172.5/havanabook/">Social Habana Book</a></li>
			<li role="presentation"><a role="menuitem" tabindex="-1" href="http://192.168.176.4/sigueme">Sigueme</a></li>
          </ul>
        </li>
        <li class="dropdown">
          <a id="drop5" role="button" data-toggle="dropdown" href="#">Documentacion <b class="caret"></b></a>
		  
          <ul id="menu2" class="dropdown-menu" role="menu" aria-labelledby="drop5">
		    <li role="presentation"><a role="menuitem" tabindex="-1" href="http://192.168.176.2:8002/wikipedia_en_all_02_2014/">Wikipedia</a></li>
		    <li role="presentation" class="divider"></li>
            <li role="presentation"><a role="menuitem" tabindex="-1" href="http://192.168.32.1:8080/stackoverflow">Stack Overflow</a></li>
            <li role="presentation"><a role="menuitem" tabindex="-1" href="http://192.168.32.1/zeal/">Zeal</a></li>
            <li role="presentation"><a role="menuitem" tabindex="-1" href="http://192.168.32.1:9292/">DevDocs</a></li>            
            <li role="presentation"><a role="menuitem" tabindex="-1" href="http://192.168.32.1/w3schools">W3Schools</a></li>
			<li role="presentation"><a role="menuitem" tabindex="-1" href="http://192.168.32.1/jqapi/">Jqapi</a></li>
			<li role="presentation"><a role="menuitem" tabindex="-1" href="http://192.168.32.1/zeal/docset/Bootstrap/Contents/Resources/Documents/getbootstrap.com/index.html">Bootstrap 3</a></li>
		   </ul>
        </li>
        <li class="dropdown">
          <a id="drop6" role="button" data-toggle="dropdown" href="#">Ftp's <b class="caret"></b></a>
          <ul id="menu3" class="dropdown-menu" role="menu" aria-labelledby="drop6">
            <li role="presentation"><a role="menuitem" tabindex="-1" href="http://twitter.com/fat">Ftp comunidad NetLab (32.2)</a></li>
            <li role="presentation"><a role="menuitem" tabindex="-1" href="http://twitter.com/fat">Ftp SNET I(128.1)</a></li>
            <li role="presentation"><a role="menuitem" tabindex="-1" href="http://twitter.com/fat">Ftp SNET II(128.2)</a></li>
          </ul>
        </li>
		<li class="dropdown">
          <a id="drop7" role="button" data-toggle="dropdown" href="#">Jabber <b class="caret"></b></a>
          <ul id="menu4" class="dropdown-menu" role="menu" aria-labelledby="drop7">
            <li role="presentation"><a role="menuitem" tabindex="-1" href="http://twitter.com/fat">XMPP 32.1 (NetLab)</a></li>
            <li role="presentation"><a role="menuitem" tabindex="-1" href="http://twitter.com/fat">XMPP 128.1 (SNET)</a></li>
          </ul>
        </li>
		<li class="dropdown">
          <a id="drop8" role="button" data-toggle="dropdown" href="#">Juegos <b class="caret"></b></a>
          <ul id="menu4" class="dropdown-menu" role="menu" aria-labelledby="drop8">
            <li role="presentation"><a role="menuitem" tabindex="-1" href="http://twitter.com/fat">Dota I</a></li>
            <li role="presentation"><a role="menuitem" tabindex="-1" href="http://twitter.com/fat">Dota II</a></li>
			<li role="presentation"><a role="menuitem" tabindex="-1" href="http://twitter.com/fat">Dota III</a></li>
          </ul>
        </li>
      </ul> <!-- /tabs --> 
 </div>
   <div class="container bs-docs-container" id="contenido">
<?php
include 'config.php';//Inclusion del archivo de configuracion que contiene los datos de coneccion al server
include 'include\general.php';//inclusion del archivo de funciones basicas a la bd
$coneccion=mysql_connect($server,$user,$password);//inicializa la coneccion
$db=mysql_select_db("ftpindexer");//selecciona la bd

//todo Por hacer: hay que crear un campo para almecenar el nombre de la bd en el archivo config.php para evitar 
//tener que actualizarlo en todos los archivos


if($_GET)
{
  
if(isset($_GET["searchedtext"]) && $_GET["searchedtext"]=='')
goto tag;
if(isset($_GET["searchedtext"]) && strlen($_GET["searchedtext"])<1)
{
	echo('<div class="alert alert-success fade in alert-dismissable"><button class="close" aria-hidden="true" data-dismiss="alert" type="button">&times;</button>'.
    '<h4 id="infoalerttitle">Error</h4><p id="infoalerttext">No se admiten cadenas de menos de 3 caracteres para la busqueda.</p></div>');
	goto tag;
}
?>
<div id="grafiti">
   CAROLA 
</div>
<!-- <img src="media/jpg/logo2.jpg" style="position:relative;left:-10px"><br><br> -->
<form class="form-inline" action="search.php" method="get" style="width:780px">
<div class="form-group">
<!--  <input class="form-control" type="text" name="searchedtext" placeholder="Escriba aqui el texto que desea buscar" 
 style="width:600px;"   value="<?php /*echo($_GET["searchedtext"]);*/ ?>"> -->

 <input class="typeahead form-control" type="text" name="searchedtext" placeholder="Escriba aqui el texto que desea buscar"
 style="margin: 0 auto; width:480px;" data-provide="typeahead" data-items="10" 
 value="<?php if(isset($_GET["searchedtext"])) echo($_GET["searchedtext"]); ?>">
</div><div class="form-group">
&nbsp;<button type="submit" class="btn btn-primary"><img src="media/png/search2.png"> Buscar</button>
</div>



</form>
<!-- <div class="pagination">
  <ul>
    <li><a href="#">Prev</a></li>
    <li><a href="#">1</a></li>
    <li><a href="#">2</a></li>
    <li><a href="#">3</a></li>
    <li><a href="#">4</a></li>
    <li><a href="#">Next</a></li>
  </ul>
</div> -->
<?php

  if(isset($_GET["searchedtext"])){
  //setlocale(LC_ALL, 'es_ES');
  $ftps=mysql_query("select * from ftps where activo=1"); 
  $ftpscount=mysql_num_rows($ftps);
  $rescount=0;
  if($ftpscount==0)
    echo("Aun no se han creado los indices para ningun ftp. Denle el berro a los Admins");
  else
  for($i=0;$i<$ftpscount;$i++)
  {
    $ftpsrow=mysql_fetch_array($ftps);
    $currentftp=$ftpsrow['user'].'@'.$ftpsrow['direccion_ip'];
    //echo("select * from ftptree where nombre LIKE '%".strtr(addslashes($_GET["searchedtext"]),' ','%')."%' and idftp=".$ftpsrow['id']);
    $result=mysql_query("select * from ftptree where nombre LIKE '%".strtr(addslashes($_GET["searchedtext"]),' ','%')."%' and idftp=".$ftpsrow['id']);  
    $count=mysql_num_rows($result);
    if($count!=0)
    {
      echo("<p id=currentftp><b>".$currentftp."</b></p>");
      for($j=0;$j<$count;$j++)
      {
        $row=mysql_fetch_array($result);
        $rescount++;
        echo("<a href='ftp://".$ftpsrow['user'].'@'.$ftpsrow['direccion_ip'].$row['path'].'/'.$row['Nombre']."' target='_blank'>".$row['path'].'/'.$row['Nombre']."</a><br>");
      }
    }
  }
  echo("<p id=cantresult>Se han encontrado <b>".$rescount."</b> resultados.</p>");
  }

}
else
{
tag:
?>

 <center>
 <div id="grafiti">
   CAROLA 
</div>
<div id="subtitle">FTP INDEXER</div>
<!-- <img src="media/jpg/logo.jpg" style="position:relative;left:-10px;"> -->
<br><br>
<form class="form-inline" action="search.php" method="get" style="width:780px">
<div class="form-group">
 <input class="typeahead form-control" type="text" name="searchedtext" placeholder="Escriba aqui el texto que desea buscar"
 style="margin: 0 auto; width:480px;" data-provide="typeahead" data-items="10" 
 value="<?php if(isset($_GET["searchedtext"])) echo($_GET["searchedtext"]); ?>">
</div><div class="form-group">
&nbsp;<button type="submit" class="btn btn-primary"><img src="media/png/search2.png"> Buscar</button>
</div>
</form>
<?php
}
?>

<script type="text/javascript">


  var prev_search=[]; ///> arreglo de las sugerencias, ira cambiando a medida que se escribe en el input y se piden nuevas sugerencias
  var opt= { source: prev_search  }; ///> opciones que se le pasan al typeahead
  $('.typeahead').typeahead(opt);  ///> inicializacion del typeahead con las opciones de arriba (las por default)

  var caracteres= 1;///> cantidad de caracteres que el usuario va escribiendo, usado solo para ir haciendo la busqueda cada 2 caracteres escritos
  var cant=10; ///> cantidad de items (sugerencias) que devuelve la busqueda mientras se va escribiendo codigo

  /*
  Usado para restringir el numero de elemntos que se muestran a la cantidad de sugerencias de la busqueda
  Llevado a cabo porque el typeahead de bootstrap no permite cambiar el numero de items que muestra de forma dinamic
  */
  $('.typeahead').keyup(function(event) {      
      if(cant >= 0)
        $('ul.typeahead li:gt('+cant+')').remove();
      else{
        $('ul.typeahead li').remove();    
        $('ul.typeahead').addClass('hide');
      }  
      
  });
    
  /*
  Se encarga de realizar el proceso de buscar las sugerencias que matchean con lo que se escribe en el input
  Usa AJAX y solicita en formato json las sugerencias
  */
  $('.typeahead').keydown(function(){
    if(caracteres++ == 2){
      caracteres=0;
    cant=0;
    $.ajax(
    {   
      url : "autocompletamiento.php",   
      data : {    
            text : $('.typeahead').prop('value'),
      },
      type : "POST",  
      dataType : "json",     
      success : function( json ) 
            {  
                  var sourceX= [];

                  for(var i=0; i<10; i++){ ///> 10 es el limite propuesto de items a mostrar como sugerencias
                    if(json[i]){ ///> si el arreglo json esta definido en la pos i, indica que es una sugerencia                                          
                      prev_search[i]=json[i]; 
                    }
                    else
                      break;
                  }
                  // console.log("i > "+i);
                  cant=i-1; ///> almacena la cantidad real de sugerencias que vienen en el response al pedido asincrono anterior
                  
            },   
      error : function( xhr, status ) 
            {    
              console.log("HERE 2 error");
            },   
      complete : function( xhr, status ) 
            { 
              console.log("HERE COMPLETE")         
            } 
    });
      
       }       
    
  });
</script>

</div>
<center>
<ul class="pagination">
  <li class="disabled"><a href="#">&laquo;</a></li>
  <li class="active"><a href="#">1 <!-- <span class="sr-only">(current)</span> --></a></li>
  <li class=""><a href="#">2 </a></li>
  <li class=""><a href="#">3 </a></li>
  
</ul>
<footer class="footer" id="colophon" role="contentinfo">
<a href="ftp.php" target="_blank"><button type="button" class="btn btn-link">Administrar</button></a><br>
<!-- <img src="remo.png"> -->
</footer> 
</body>
</html>