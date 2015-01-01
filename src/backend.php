<?php

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;

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

	if($form->isValid()){

         // return new Response(var_dump($app['database']->insertFtp($form->getData())));
		
        //Insertar los valores
        $insert_result = $app['database']->insertFtp($form->getData());
        if($insert_result == '00000'){

            $errorMessage['state'] = 'success';
            $errorMessage['message'] = 'FTP insertado satisfactoriamente.';

        }else{
           
          $errorMessage['state'] = 'danger';
          $errorMessage['message'] = 'Ha ocurrido un error a insertar el FTP: '.$insert_result[2]; 
        }


	}

    if(!isset($errorMessage)){
        $errorMessage['state'] = 'danger';
        $errorMessage['message'] = 'Ha ocurrido un error a insertar el FTP: '. $form->getErrorsAsString();

    } 

    $app['session']->getFlashBag()->add($errorMessage['state'], $errorMessage['message'] );

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
        ->add('descripcion', 'text' , array(
            'constraints' => new Assert\NotBlank()
         ))
        ->add('ip', 'text', array(
            'constraints' => new Assert\Ip()
        ))
        ->add('activo', 'checkbox' , array('required' => false ))
        ->add('usuario', 'text', array(
            'constraints' => new Assert\NotBlank()
            ))
        ->add('pass', 'password', array(
            'constraints' => array(new Assert\NotBlank(),new Assert\Length("min=5") )    
            ))
     ->getForm();

     return $form;
};

return $backend;