<?php
if(!(isset($_COOKIE["t"]))){
    setcookie("t", 'white', time()+(3600*24*365));
}
if(isset($_GET['t'])){
    setcookie("t", 0,0);
    if($_GET["t"]=='b')
        setcookie("t", 'black', time()+(3600*24*365));
    else if( $_GET['t']=='w')
        setcookie("t", 'white', time()+(3600*24*365));
    header('Location: search.php');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/docs.css" rel="stylesheet">
    <link href="css/carola_site.css" rel="stylesheet">
    <link href="css/carola_site_<?php echo($_COOKIE["t"]);?>.css" rel="stylesheet">
</head>
<body>
<script src="scripts/js/jquery.js"></script>
<script src="scripts/js/modal.js"></script>
<script src="scripts/js/bootstrap.js"></script>
<script src="scripts/js/alert.js"></script>
<script src="scripts/js/bootstrap-typeahead.js"></script>
<script src="scripts/js/mystorage.js"></script>
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
        <li class="dropdown pull-right">
            <a id="drop8" role="button" data-toggle="dropdown" href="#">Estilo <b class="caret"></b></a>
            <ul id="menu4" class="dropdown-menu" role="menu" aria-labelledby="drop8">
                <li role="presentation"><a role="menuitem" tabindex="-1" href="search.php?t=w">Carola White</a></li>
                <li role="presentation"><a role="menuitem" tabindex="-1" href="search.php?t=b">Carola Black</a></li>
            </ul>
        </li>
    </ul> <!-- /tabs -->
</div>
<div class="container bs-docs-container" id="contenido">


<div id="carola-search-box" class="carola-search-box-center">
<div id="grafiti">
    CAROLA
</div>
<div id="subtitle" class="subtitle-center">FTP INDEXER</div>
<!-- <img src="media/jpg/logo.jpg" style="position:relative;left:-10px;"> -->
<br><br>
<form id="carola-form" class="form-inline" action="search.php" method="get" style="width:780px">
    <div class="form-group">
        <input class="typeahead-suggestion typeahead form-control" type="search" name="searchedtext" placeholder="Escriba aqui el texto que desea buscar"
               style="margin: 0 auto; width:480px;" data-provide="typeahead" data-items="10"
               value="<?php if(isset($_GET["searchedtext"])) echo($_GET["searchedtext"]); ?>">
    </div><div class="form-group">
        &nbsp;<button type="submit" class="btn btn-primary"><img src="media/png/search2.png"> Buscar</button>
    </div>
</form>


<script type="text/javascript">


var storageSupported= supportsStorage();
function esCaracterValido(caracter){
    if(caracter.charCodeAt(0) >= 65 && caracter.charCodeAt(0)<=90)
        return true;
    else if(caracter.charCodeAt(0) >= 97 && caracter.charCodeAt(0)<=122)
        return true;
    else if(caracter.charCodeAt(0) == 241)  //la ñ
        return true;
    else
        return false;
}

var prev_search=[]; ///> arreglo de las sugerencias, ira cambiando a medida que se escribe en el input y se piden nuevas sugerencias
var opt= { source: prev_search  }; ///> opciones que se le pasan al typeahead
$('.typeahead-suggestion').typeahead(opt);  ///> inicializacion del typeahead con las opciones de arriba (las por default)

//  var caracteres= 1;///> cantidad de caracteres que el usuario va escribiendo, usado solo para ir haciendo la busqueda cada 2 caracteres escritos
var cant=-1; ///> cantidad de items (sugerencias) que devuelve la busqueda mientras se va escribiendo codigo, -1 por defecto (no muestra ninguna)
var lastCantWords=0;
var cantCharBeforeSuggest = 3;
function restarSuggestions(){
    cant=-1;
    lastCantWords=0;

}
/*
 Usado para restringir el numero de elemntos que se muestran a la cantidad de sugerencias de la busqueda
 Llevado a cabo porque el typeahead de bootstrap no permite cambiar el numero de items que muestra de forma dinamic
 */
function resizeSuggestions() {
    if (cant >= 0){
        $('ul.typeahead').removeClass('hide');
        $('ul.typeahead li').removeClass('hide');
        $('ul.typeahead li:gt(' + cant + ')').addClass('hide');
    }
//                $('ul.typeahead li:gt(' + cant + ')').remove();
    else {
//                $('ul.typeahead li').remove();
        $('ul.typeahead li').addClass('hide');
        $('ul.typeahead').addClass('hide');
    }
}
var ENTER_KEY_IS_PRESSED=false;
$('.typeahead-suggestion').keydown(function(event) {
    var keyCode = event.keyCode || event.which;
    if(keyCode == 13) //ENTER KEY
    {
        ENTER_KEY_IS_PRESSED=true;
        if($('ul.typeahead').hasClass('hide')){
            var value=$('.typeahead-suggestion').prop('value');
            $('ul.typeahead li').attr('data-value',value);
            $('ul.typeahead li a').html(value);
        }
        restarSuggestions();
    }
    else{
        ENTER_KEY_IS_PRESSED=false;
        resizeSuggestions();
//            console.log("==================== in KEYDOWN: cant= "+cant);
        var typeaheadValue= $('.typeahead').prop('value');
        if(typeaheadValue.length<=1){
            restarSuggestions();
            console.log("restarSuggestions executed");
        }
    }
});
var timeoutTest= 10000;


/*
 Se encarga de realizar el proceso de buscar las sugerencias que matchean con lo que se escribe en el input
 Usa AJAX y solicita en formato json las sugerencias
 */
var onlyKeyUp=false;
var lastPetition = {};
$('.typeahead-suggestion').keyup(function(event){
    if(!onlyKeyUp){
        var keyCode = event.keyCode || event.which;

        resizeSuggestions();

        var typeaheadValue= $('.typeahead').prop('value');

        var arrayOfStrings = typeaheadValue.split(' ');
        console.log("arrayOfStrings => "+arrayOfStrings);
        var stringToSearch= "";
        var cantWords= arrayOfStrings.length;
        var wordsToPeticion = 0;
        var size = cantWords;
        for (var i = 0; i <size ; i++) {
            if(arrayOfStrings[i].length == 0 ){
                console.log("elimino");
                cantWords--;
                continue;
            }
            else if(arrayOfStrings[i].length >= cantCharBeforeSuggest || (arrayOfStrings[i].length==1 && !esCaracterValido(arrayOfStrings[i]))){
                wordsToPeticion++;

            }
            if(i>0 && i<size)
                stringToSearch+= " ";
            stringToSearch+=arrayOfStrings[i];
        };
        var lastWord;
        if(keyCode == 32){ //espacio
            lastWord= arrayOfStrings[arrayOfStrings.length - 2];
            console.log("lastWord: ["+lastWord+"]");
        }
//            if(lastCantWords == cantWords)
//                lastCantWords --;

        console.log("stringToSearch => "+stringToSearch);
        console.log("lastCantWords: "+lastCantWords+" , cantWords: "+cantWords+" , wordsToPeticion: "+wordsToPeticion);
//            if(cantWords<lastCantWords) //mostrar una busqueda realizada
//            {
        var cookieObject;
        if(storageSupported)
            cookieObject = JSON.parse(localStorage.getItem(stringToSearch));
        else
            cookieObject = JSON.parse(getCookie(stringToSearch));
        if(cookieObject && (lastCantWords!=wordsToPeticion || (lastCantWords==wordsToPeticion && lastWord))){
            console.log(">>>>>>>>>>>>>>> cant_elements of a cookieObject: "+cookieObject.cant_elements);
            cant= cookieObject.cant_elements;
//                    resizeSuggestions();
            for(var i=0; i<=cant; i++){
                prev_search[i]= cookieObject.items[i];
            }
            lastCantWords=wordsToPeticion;
            onlyKeyUp=true;
            $('.typeahead-suggestion').keyup();
            resizeSuggestions();
            onlyKeyUp=false;
        }

//            }
        else if(stringToSearch.length>0
            && (wordsToPeticion>lastCantWords
            || (wordsToPeticion==lastCantWords
            && lastWord && lastWord.length>(2*cantCharBeforeSuggest)
            )
            )
            && !ENTER_KEY_IS_PRESSED){
            lastCantWords=wordsToPeticion;
            if(cant>=-1){
                if(lastWord)
                    console.log("in cant>=-1: and lastWord!=NULL");
                cant=-1;

                var petitionXHR= $.ajax(
                    {
                        url : "autocompletamiento.php",
                        data : {
                            text : stringToSearch
                        },
                        type : "POST",
                        dataType : "json",
                        beforeSend: function (xhr)
                        {
                            if(lastPetition.petition){
                                lastPetition.petition.abort();
                            }
//                            $('.typeahead-suggestion').data('xhr',peticionXHR);
//                                    var lastXHR= $('.typeahead-suggestion').data('xhr');
//                                    if(lastXHR){
//                                        console.log("->>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>se va a borrar => "+lastXHR);
//                                        lastXHR.abort();
//                                    }
//                                    $('.typeahead-suggestion').data('xhr',$(this));
                        },
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
                            cant=i-1; ///> almacena la cantidad (usado por conveniencia en cuanto a los indices de los ul) de sugerencias que vienen en el response al pedido asincrono anterior
                            if(cant!=-1){
                                var prev_result = {
                                    cant_elements: cant,
                                    items: prev_search
                                };

                                if(storageSupported)
                                    localStorage.setItem(stringToSearch, JSON.stringify(prev_result));
                                else
                                    setCookie(stringToSearch, JSON.stringify(prev_result));
                                onlyKeyUp=true;
                                $('.typeahead-suggestion').keyup();
                                onlyKeyUp=false;
                            }
//                                lastPetition.complete_code = 1;
//
                        },
                        error : function( xhr, status )
                        {
                            console.log("HERE 2 error");
                        },
                        complete : function( xhr, status )
                        {
                            console.log("HERE COMPLETE");
                            resizeSuggestions();
                        }
                    });

                lastPetition.petition= petitionXHR;
            }
        }
//            if(lastCantWords!=wordsToPeticion)
//            lastCantWords=cantWords;
    }
});
</script>
<?php
include 'config.php';//Inclusion del archivo de configuracion que contiene los datos de coneccion al server
include 'include\general.php';//inclusion del archivo de funciones basicas a la bd
$coneccion=mysql_connect($server,$user,$password);//inicializa la coneccion
$db=mysql_select_db("ftpindexer");//selecciona la bd

//todo Por hacer: hay que crear un campo para almecenar el nombre de la bd en el archivo config.php para evitar
//tener que actualizarlo en todos los archivos


if($_GET)
{

    if(isset($_GET["searchedtext"]) && strlen($_GET["searchedtext"])<1)
    {
        echo('<div class="alert alert-success fade in alert-dismissable"><button class="close" aria-hidden="true" data-dismiss="alert" type="button">&times;</button>'.
            '<h4 id="infoalerttitle">Error</h4><p id="infoalerttext">No se admiten cadenas de menos de 3 caracteres para la busqueda.</p></div>');

    }
    else if(isset($_GET["searchedtext"])){
        ?>
        <script type="text/javascript">
            $('#carola-search-box').removeClass('carola-search-box-center');
            $('#subtitle').removeClass('subtitle-center');
            $('#carola-search-box').addClass('carola-search-box-left');
            $('#subtitle').addClass('subtitle-left');
        </script>
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
                    echo("<p id=currentftp><b>".$currentftp."</b></p>");
                    echo("<div id=".$ftpsrow['id']." class='ftp_results'>");
                    for($j=0;$j<$count;$j++)
                    {
                        $row=mysql_fetch_array($result);
                        $rescount++;
                        echo("<a href='ftp://".$ftpsrow['user'].'@'.$ftpsrow['direccion_ip'].$row['path'].'/'.$row['Nombre']."' target='_blank'>".$row['path'].'/'.$row['Nombre']."</a><br>");
                    }
                    echo("</div>");
                }
            }
        echo("<p id=cantresult>Se han encontrado <b>".$rescount."</b> resultados.</p>");
    }

}

?>
</div>
</div>
<!-- <ul class="pagination">
  <li class="disabled"><a href="#">&laquo;</a></li>
  <li class="active"><a href="#">1 <span class="sr-only">(current)</span></a></li>
  <li class=""><a href="#">2 </a></li>
  <li class=""><a href="#">3 </a></li>
  
</ul> -->
<footer class="footer" id="colophon" role="contentinfo">
    <a href="ftp.php" target="_blank"><button type="button" class="btn btn-link">Administrar</button></a><br>
    <!-- <img src="remo.png"> -->
</footer>
</body>
</html>