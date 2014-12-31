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

$backend->post('/insert', function () use ($app) {

	$form = $app['form'];

	$form ->bind($app['request']);

	// return new Response(var_dump($form->getData()));

	if($form->isValid()){
		//Insertar los valores
		$app['database']->insertFtp($form->getData());
		$app['session']->getFlashBag()->add('success', 'FTP insertado satisfactoriamente.');

	}

	return $app->redirect($app['url_generator']->generate('admin'));

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
        ->add('activo', 'checkbox' , array('required' => false ))
        ->add('usuario')
        ->add('pass', 'password')
     ->getForm();

     return $form;
};

return $backend;