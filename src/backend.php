<?php

use Symfony\Component\HttpFoundation\Response;

// Controladores relacionados con la parte de administración del sitio web
$backend = $app['controllers_factory'];

// Protección extra que asegura que al backend sólo acceden los administradores
$backend->before(function () use($app) {
    if (!$app['security']->isGranted('ROLE_ADMIN')) {
        return new RedirectResponse($app['url_generator']->generate('homepage'));
    }
});

//Portada de administración
$backend->get('/', function () use ($app) {

	$results =  $app['database']->getFtps();

    return $app['twig']->render('backend/index.html', array(
    	'results' => $results,
    	'form'  => $app['form']->createView()
    	));

})->bind('admin');

$backend->match('/insert', function () use ($app) {



})->bind('insert');

//Logout
$app->get('/logout', function () use ($app) {
    return $app->redirect($app['url_generator']->generate('homepage'));
})->bind('logout');


/**
 * Función para crear el formulario de los ftps
 */
$app['form'] = function() use ($app){

	$form = $app['form.factory']->createBuilder('form')
        ->add('descripcion')
        ->add('ip')
        ->add('activo', 'checkbox')
        ->add('usuario')
        ->add('contrasenna')
     ->getForm();

     return $form;
};

return $backend;