$(function(){
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
        $('ul.typeahead li.active').removeClass('active');
        $('ul.typeahead').removeClass('hide');
        $('ul.typeahead li').removeClass('hide');
        $('ul.typeahead li:gt(' + cant + ')').addClass('hide');

    }

    else {
        $('ul.typeahead li').addClass('hide');
        $('ul.typeahead').addClass('hide');
    }
}
var ENTER_KEY_IS_PRESSED=false;
$('.typeahead-suggestion').keydown(function(event) {
    var keyCode = event.keyCode || event.which;
    if(keyCode == 13) //ENTER KEY
    {
//        console.log('comprueba 133333333 aki ENTER_KEY_IS_PRESSED');
        ENTER_KEY_IS_PRESSED=true;
        var value=$('.typeahead-suggestion').prop('value');
        if($('ul.typeahead').hasClass('hide') || !($('ul.typeahead li').hasClass('active'))){
//            console.log('comprueba 133333333 aki !$("ul.typeahead li.active")');
            $('ul.typeahead li').attr('data-value',value);
            $('ul.typeahead li a').html(value);

            $('#carola-form .btn').click();
        }

        restarSuggestions();
    }
    else{
        ENTER_KEY_IS_PRESSED=false;
//        resizeSuggestions();
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
    var keyCode = event.keyCode || event.which;


    if(!onlyKeyUp && keyCode != 38 && keyCode!=40 && keyCode!=13){
        console.log("STARTING ANALISYS OF LAST CHAR");


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
                            url : autocomplete_route,
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

})

/*Para cargar dinamicamente los estilos cuando el usuario lo desee, 
y así no tener que recorrer el directorio css en cada ejecucion de carola*/
$( document ).ready(function() {
if(ajax_styles_route)
    $('li#styles').click(function(event) {
        $('#estilos').load(ajax_styles_route);
        ajax_styles_route = null;
    });
});

