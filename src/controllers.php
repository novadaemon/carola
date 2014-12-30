<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

//Request::setTrustedProxies(array('127.0.0.1'));

//Cambiar el estilo
$app->before(function(Request $request) use($app){

    if(!$app['session']->has('style')){

        if(!$request->cookies->has('carola_style')){

            $style = 'white';
            $cookie = new Cookie('carola_style', $style, new \DateTime('now + 364 days'));

        }else{

            $style = $request->cookies->get('carola_style');
            
        }

        $app['session']->set('style', $style);  

    }elseif($request->query->has('style')){

        $style = $request->query->get('style');
        $app['session']->set('style', $style);
        $cookie = new Cookie('carola_style', $style, new \DateTime('now + 364 days'));

    }   

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

//Ruta para las búsquedas
$app->get('/search/{searchedtext}', function () use ($app) {
    return $app['twig']->render('index.html', array( 'results' => $results ));
})
->bind('search');





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
