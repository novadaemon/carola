<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Tools;

// Administración
$app->mount('/admin', include 'backend.php');

//Filtro que se aplica antes de las acciones para cambiar el estilo
$app->before(function(Request $request) use($app){

    /**
     * Comprobar si existe la variable de sessión que guarda el estilo
     */
    if(!$app['session']->has('style')){

        /**
         * Comprobar si existe la cookie 
         */
        if(!$request->cookies->has('carola_style')){

            /**
             * Si no existe setear el estilo white por defecto y crear la cookie
             * @var string
             */
            $style = 'Flatly';
            $cookie = new Cookie('carola_style', $style, new \DateTime('now + 364 days'));

        }else{

            /**
             * Si existe la cookie optener el valor del estilo
             * @var string
             */
            $style = $request->cookies->get('carola_style');
            
        }

        /**
         * Guardar en una variable de sessión el valor del estilo
         */
        $app['session']->set('style', $style);  

    /**
     * Si el usuario ha cambiado el estilo...
     */
    }elseif($request->query->has('style')){

        /**
         * Obtener el valor y guardarlo en la sessión y en la cookie
         * @var string
         */
        $style = $request->query->get('style');
        $app['session']->set('style', $style);
        $cookie = new Cookie('carola_style', $style, new \DateTime('now + 364 days'));

    }   

    /**
     * Crear la respuesta y si se ha definido la cookie setearla en los encabezados
     * @var Response
     */
    $response = new Response();
    if(isset($cookie)) $response->headers->setCookie($cookie);
    $app['response'] = $response;
});

//Ruta homepage
$app->get('/', function () use ($app) {

    return $app['twig']->render('index.html', array(), $app['response']);
    
})
->bind('homepage');

/**
 * Ruta para las búsquedas
 */
$app->get('/search/', function () use ($app) {

    $results = array();
    
    $key = $app['request']->query->get('searchedtext');
    $ftps_ = $app['request']->query->get('ftps');
    $exts_ = $app['request']->query->get('exts');
    if (!strstr($key, '"')) {
        //busqueda por keywords, sin comillas
        $key_ = Tools::stemPhrase($key);
        # code...
    }
    else 
    {//busqueda exacta, con comillas
        $key_[] = trim($key, '"');//le quito las comillas a lo que puso el usuario
    }
    

    if(strlen($key) > 2){
        //Obtener el número de registros total de la consulta de la palabra actual
        if(strlen($ftps_)>0 or strlen($exts_)>0 )//en caso de haber algun filtro seeccionado
            $results = $app['database']->searchWithFilters($key_,$ftps_, $exts_);  
        else//en caso de que solo sean las keywords
            $results = $app['database']->search($key_);  
    }

    $total = count($results);
    
    /**
     * Optener los ftps y las extensiones
     */
    $ftps = $exts = array();
    foreach ($results as $value) {
        //ftps
        if(!array_key_exists($value['ip'], $ftps)){
            $ftps[$value['ip']] = 1;
        }else{
          $ftps[$value['ip']]++;  
        };

        //extensiones
        if(!array_key_exists($value['ext'], $exts)){
            $exts[$value['ext']] = 1;
        }else{
          $exts[$value['ext']]++;  
        };
    }
    arsort($ftps); arsort($exts);
    $exts = array_slice($exts,0,15);

    //Paginar el resultado
    $offset = $app['request']->query->has('offset') ? $app['request']->query->get('offset') : 0;
    $limit = $app['request']->query->has('limit') ? $app['request']->query->get('limit') : 30;
 /*   
    //Filtrar los resultados
    $results = $app['database']->filter($key, $offset, $limit);
*/
    $results_slice = array_slice($results, $offset, $limit);

    return $app['twig']->render('results.html', array( 
        'total' => $total,
   //     'results' => $results,
        'results' => $results_slice,
        'ftps' => $ftps,
        'exts'  => $exts

         ), $app['response']);

})
->bind('search');

/**
 * Acción para el autocompletamiento
 */
$app->post('/automplete', function() use ($app){
    
    $key = $app['request']->get('text');
    $results = $app['database']->autocomplete($key);

    return new JsonResponse($results);

})->bind('autocompletamiento');


$app->error(function (\Exception $e, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    // 404.html, or 40x.html, or 4xx.html, or error.html
    $templates = array(
        'errors/'.$code.'.html',
        'errors/'.substr($code, 0, 2).'x.html',
        'errors/'.substr($code, 0, 1).'xx.html',
        'errors/default.html',
    );

    return new Response($app['twig']->resolveTemplate($templates)->render(array('code' => $code)), $code);
});



/*
* Ruta para cargar los estilos por ajax, asi se evita leer el directorio css
* cada vez que se ejecute carola, solo se usara cuando el user quiera cambiar el estilo
* Usa el servicio ListThemes declarado abajo
*/
$app->get('/ajax/styles', function() use ($app) { 
    return $app['twig']->render('ajax_styles.html');
})->bind('ajax_styles');;
