<html>
<head>
<meta charset="UTF-8" />
<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/docs.css" rel="stylesheet">
</head>
<body>
 <script src="scripts/js/jquery.js"></script>
 <script src="scripts/js/modal.js"></script>
 <script src="scripts/js/bootstrap.js"></script>
 <script src="scripts/js/alert.js"></script>
 <div style="position:fixed;top:0px;">	
 <img src="newbeta.png" style="position:absolute;display:block;">
      <ul class="nav nav-pills" style="margin-left:40px">	  
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
include 'config.php';
include 'include\general.php';
$coneccion=mysql_connect($server,$user,$password);
$db=mysql_select_db("ftpindexer");
if($_GET)
{
if($_GET["searchedtext"]=='')
goto tag;
if(strlen($_GET["searchedtext"])<1)
{
	echo('<div class="alert alert-success fade in alert-dismissable"><button class="close" aria-hidden="true" data-dismiss="alert" type="button">&times;</button>'.
    '<h4 id="infoalerttitle">Error</h4><p id="infoalerttext">No se admiten cadenas de menos de 3 caracteres para la busqueda.</p></div>');
	goto tag;
}
?>

<img src="media/jpg/logo2.jpg" style="position:relative;left:-10px"><br><br>
<form class="form-inline" action="search.php" method="get" style="width:780px">
<div class="form-group">
 <input class="form-control" type="text" name="searchedtext" placeholder="Escriba aqui el texto que desea buscar" style="width:600px"   value="<?php echo($_GET["searchedtext"]); ?>">
</div><div class="form-group">
&nbsp;<button type="submit" class="btn btn-primary"><img src="media/png/search2.png"> Buscar</button>
</div>
</form>
<?php
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
			echo("<p><b>".$currentftp."</b></p>");
			for($j=0;$j<$count;$j++)
			{
				$row=mysql_fetch_array($result);
				$rescount++;
				echo("<a href='ftp://".$ftpsrow['user'].'@'.$ftpsrow['direccion_ip'].$row['path'].'/'.$row['Nombre']."' target='_blank'>".$row['path'].'/'.$row['Nombre']."</a><br>");
			}
		}
	}
	echo("<p>Se han encontrado <b>".$rescount."</b> resultados.</p>");
	

}
else
{
tag:
?>
 <center>
<img src="media/jpg/logo.jpg" style="position:relative;left:-10px">
<br><br>
<form class="form-inline" action="search.php" method="get" style="width:780px">
<div class="form-group">
 <input class="form-control" type="text" name="searchedtext" placeholder="Escriba aqui el texto que desea buscar" style="width:600px">
</div><div class="form-group">
&nbsp;<button type="submit" class="btn btn-primary"><img src="media/png/search2.png"> Buscar</button>
</div>
</form>
<?php
}
?>

</div>
<center>
<footer id="colophon" role="contentinfo">
<a href="ftp.php" target="_blank"><button type="button" class="btn btn-link">Administrar</button></a><br>
<img src="remo.png">
</footer> 
</body>
</html>