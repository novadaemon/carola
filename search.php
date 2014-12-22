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
<script src="scripts/js/jquery.js">
</script>
<script>
    $(document).ready(function(){

        $('html').css('height','100%');
////        $('html').removeAttr('style');
//        var is_firefox = navigator.userAgent.toLowerCase().indexOf('firefox') > -1;
//        if(is_firefox)
//            console.log("si es firefox");
//        else
//            console.log("no es firefox");

        function explore(e) {
            alert(encodeURI(e));
            window.location = encodeURI(e);
        }
        function filterchanged() {
            alert('');
        }
        $(document).ready(function () {
                if ($('tr').length - 1 > 10)

                    $('#mypaginator').clone().insertAfter($('tr:last'));
//                $('#inputsearch').autocomplete({source: 'scripts/php/autocomplete.php', delay: 500});
            }
        );
    });
</script>
<script src="scripts/js/modal.js"></script>
<script src="scripts/js/bootstrap.js"></script>
<script src="scripts/js/alert.js"></script>
<script src="scripts/js/bootstrap-typeahead.js"></script>
<script src="scripts/js/mystorage.js"></script>
<script src="scripts/js/scrollspy.js"></script>
<div id="carola-nav" style="position:fixed;top:0px;">


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
                <li role="presentation"><a role="menuitem" tabindex="-1" href="search.php?t=w">BlueWhite </a></li>
                <li role="presentation"><a role="menuitem" tabindex="-1" href="search.php?t=b">BlackTea </a></li>
            </ul>
        </li>
    </ul> <!-- /tabs -->

</div>
<div class="container bs-docs-container" id="contenido">


<div id="carola-search-box" class="carola-search-box-center">
<div id="logo">
    <div id="grafiti">
        CAROLA
    </div>
    <div id="subtitle">FTP INDEXER</div>
<!--    class="subtitle-center"-->
</div>
<!-- <img src="media/jpg/logo.jpg" style="position:relative;left:-10px;"> -->
<br><br>
<!--action="filter_search.php?filter_search"-->
<form id="carola-form" class="form-inline" action="search.php" method="get" >
    <div class="form-group">
        <input class="typeahead-suggestion typeahead form-control" type="search" name="searchedtext" placeholder="Escriba aqui el texto que desea buscar"
               data-provide="typeahead" data-items="10"
               value="<?php if(isset($_GET["searchedtext"])) echo($_GET["searchedtext"]); ?>" required="true">
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
var maxItemsToSuggest = 10;
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
        console.log("==================== in KEYDOWN: cant= "+cant);
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
        console.log("STARTING ANALISYS OF LAST CHAR");
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
            console.log('_______ cant: '+cant);
            if(lastCantWords>0 && wordsToPeticion>0 && cant==-1){
                console.log("+++++++++no result petition por gusto");
            }
            else  {
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
//                            console.log("HERE BEFORE SEND");
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

                                for(var i=0; i<maxItemsToSuggest; i++){ ///> 10 es el limite propuesto de items a mostrar como sugerencias
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
        }
//            if(lastCantWords!=wordsToPeticion)
//            lastCantWords=cantWords;
    }
});
</script>



<?php

if(isset($_GET))
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
    $(document).ready(function(){
        $('html').removeAttr('style');
//        $('#logo').removeProperty('margin-left');
        $('#logo').css('margin-left','0.7em');
    });


//    $('#subtitle').removeClass('subtitle-center');
    $('#carola-search-box').addClass('carola-search-box-left');
    $('#subtitle').addClass('subtitle-left');
</script>


<?php
//setlocale(LC_ALL, 'es_ES');
include 'config.php';//Inclusion del archivo de configuracion que contiene los datos de coneccion al server
include 'include\general.php';//inclusion del archivo de funciones basicas a la bd
$coneccion=mysql_connect($server,$user,$password);//inicializa la coneccion
$db = mysql_select_db($db_name);//selecciona la bd

$ftps = mysql_query("select * from ftps where activo=1");
$ftpscount = mysql_num_rows($ftps);

if ($ftpscount == 0)
echo("Aún no se han creado los indices para ningun ftp. Denle el berro a los Admins");
else {


$row_start = (isset($_GET['offset']) ? $_GET['offset'] : 0);


//$ftps = mysql_fetch_all( mysql_query("SELECT ftptree.idftp, Count(ftptree.idftp), ftps.direccion_ip, ftps.user FROM ftptree INNER JOIN ftps ON ftptree.idftp = ftps.id where nombre LIKE '%" . strtr(addslashes($_GET["searchedtext"]), ' ', '%') . "'"));
$ftps_rows = mysql_query("select ftps.direccion_ip as ip, count(ftps.direccion_ip) as ftp_count from ftptree INNER JOIN ftps ON ftptree.idftp = ftps.id where nombre LIKE '%" . strtr(addslashes($_GET["searchedtext"]), ' ', '%') . "%' GROUP BY ftps.direccion_ip");
$ext_rows = mysql_query("select SUBSTRING_INDEX(Nombre, '.', -1) AS ext, count(Nombre) as ext_count from ftptree where Nombre LIKE '%" . strtr(addslashes($_GET["searchedtext"]), ' ', '%') . "%' AND LENGTH(SUBSTRING_INDEX(Nombre, '.', -1)) BETWEEN 2 AND  5 GROUP BY ext ORDER BY ext_count DESC LIMIT 15");
$query = "select Nombre, Tamanho, ftps.direccion_ip as ip, path from ftptree INNER JOIN ftps ON ftptree.idftp = ftps.id where nombre LIKE '%" . strtr(addslashes($_GET["searchedtext"]), ' ', '%') . "%'";
$row_count = mysql_numrows(mysql_query($query));
//echo $query;
$rows = mysql_query($query . " LIMIT $row_start, $row_offset");
$current_ftp = -1;
?>

<div onchange="filterchanged()" class="panel panel-default" style="float: right;">
    <div class="panel-heading"><h4>FTPs</h4></div>
    <div class="panel-body">
        <ul class="list-group">
            <?php for ($i = 0; $i < mysql_numrows($ftps_rows); $i++) {
                $frow = mysql_fetch_array($ftps_rows);?>
                <li class="list-group-item">
                    <label style="font-size: small;font-weight: normal"> <input
                            type="checkbox"/> <?php echo $frow['ip']; ?>
                    </label> <span class="badge  alert-info"><?php echo $frow['ftp_count']; ?></span>
                </li>
            <?php } ?>
        </ul>
    </div>

    <div class="panel-heading"><h4>Extensiones</h4></div>
    <div class="panel-body">
        <ul class="list-group">
            <?php for ($i = 0; $i < mysql_numrows($ext_rows); $i++) {
                $frow = mysql_fetch_array($ext_rows);?>
                <li class="list-group-item">
                    <label style="font-size: small;font-weight: normal"> <input
                            type="checkbox"/> <?php echo $frow['ext']; ?>
                    </label> <span class="badge  alert-info"><?php echo $frow['ext_count']; ?></span>
                </li>
            <?php } ?>
        </ul>

    </div>
</div>

<table class='table table-responsive table-hover' style='font-size: small;width: 70% '>
    <tr id="mypaginator">
        <th colspan="2">
            <div style="padding: 5px" class="container-fluid">

                <div style="float: left;margin-top: 15px">
                    <p class='text-info'>
                        Resultados   <?php echo ($row_start + 1) . " - " . ($row_start + mysql_numrows($rows)) . " / $row_count resultados." ?>

                </div>
                <?php if ($row_count > $row_offset) { ?>
                    <div style="float: right">
                        <ul class='pagination pagination-centered pull-right'>
                            <li><a href="?searchedtext=<?php echo $_GET["searchedtext"] ?>&offset=0">&laquo;</a>
                            </li>
                            <li class="divider"></li>
                            <?php

                            for ($i = 0; $i < $row_count; $i += $row_offset)
                                if ($i > $row_start - 5 * $row_offset && $i < $row_start + 5 * $row_offset)
                                    echo "<li " . ($row_start == $i ? "class='active'" : '') . "><a href='?searchedtext=" . $_GET["searchedtext"] . "&offset=$i'>" . ($i / $row_offset + 1) . "</a></li>";
                            ?>
                            <li class=""></li>
                            <li>
                                <a href="?searchedtext=<?php echo $_GET["searchedtext"] ?>&offset=<?php echo $row_count - $row_count % $row_offset ?>">&raquo;</a>
                            </li>
                        </ul>
                    </div>
                <?php } ?>
            </div>
        </th>
    </tr>
    <?php
    for ($i = 0; $i < mysql_numrows($rows); $i++) {
        // if($ftps[$current_ftp])
        $row = mysql_fetch_array($rows);
        ?>
        <tr>
            <td>
                <div><strong>  <?php echo $row['Nombre'] ?></strong></div>
                <div style='font-size:smaller'> <?php echo "ftp://" . $row['ip'] . " | " . $row['Tamanho'] ?>
                    Kb
                </div>
            </td>
            <td>

                <a id="btndw" class="btn" role="link"
                   href='ftp:// <?php echo $row['ip'] . $row['path'] . '/' . $row['Nombre'] ?>'>
                    <span class="glyphicon glyphicon-download-alt"></span>
                </a>
                <a class="btn" href='#' onclick="explore('ftp://<?php echo $row['ip'] . $row['path'] ?>')">
                            <span class="glyphicon glyphicon-folder-open">
                </a>
            </td>
        </tr>
    <?php
    }
    echo '</table>';


    }
    mysql_close($coneccion);
    }
    }
    ?>



</div>
</div>

<footer class="footer" id="colophon" role="contentinfo">
    <a href="ftp.php" id="administrar" class="pull-left" target="_blank">Administrar</a>
    <a href="http://192.168.32.1/netlab/" id="copyright" class="pull-right" target="_blank">Thanks to netlab</a>
</footer>
</body>
</html>