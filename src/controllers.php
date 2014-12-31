<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
            $style = 'white';
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

    $content = $app['twig']->render('index.html', array());
    
    return $app['response']->setContent($content);

})
->bind('homepage');

/**
 * Ruta para las búsquedas
 */
$app->get('/search/', function () use ($app) {

    $results = array();

    $key = $app['request']->query->get('searchedtext');

    if(strlen($key) > 2){
      $results = $app['database']->search($key);  
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
    $results = array_slice($results, $offset,  $limit);

    $content = $app['twig']->render('results.html', array( 
        'total' => $total,
        'results' => $results,
        'ftps' => $ftps,
        'exts'  => $exts

         ));

    return $app['response']->setContent($content);
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
