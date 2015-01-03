<?php

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;

// Controladores relacionados con la parte de administración del sitio web
$backend = $app['controllers_factory'];

// Protección extra que asegura que al backend sólo accedan los administradores
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
    	'form'  => $app['form']->createView(),
        'form_delete' => $app['form_delete']->createView()
    	));

})->bind('admin');

$backend->post('/insert', function () use ($app) {

	$form = $app['form'];

	$form ->bind($app['request']);

	if($form->isValid()){

        //Insertar los valores
        $result = $app['database']->insertFtp($form->getData());
        if($result[0] == '00000'){

            $errorMessage['state'] = 'success';
            $errorMessage['message'] = 'FTP insertado satisfactoriamente.';

        }else{
           
          $errorMessage['state'] = 'danger';
          $errorMessage['message'] = 'Ha ocurrido un error a insertar el FTP: '.$result[2]; 
        }

	}else{

        $errorMessage['state'] = 'danger';
        $errorMessage['message'] = 'Ha ocurrido un error a insertar el FTP: '. $form->getErrorsAsString();

    } 

    $app['session']->getFlashBag()->add($errorMessage['state'], $errorMessage['message'] );

	return $app->redirect($app['url_generator']->generate('admin'));

})->bind('insert');

//FTP update
$backend->post('/update/{id}', function ($id) use ($app) {

    $form = $app['form'];

    $form ->bind($app['request']);

    if($form->isValid()){

        foreach (array_keys($form->getData()) as $key) {
              $fields[] = $key . '= ?';
            };

        // return new Response(var_dump(join(",",$fields)));    

        //Insertar los valores
        $result = $app['database']->updateFtp($form->getData(), $id);
        if($result[0] == '00000'){

            $errorMessage['state'] = 'success';
            $errorMessage['message'] = 'FTP actualizado satisfactoriamente.';

        }else{
           
          $errorMessage['state'] = 'danger';
          $errorMessage['message'] = 'Ha ocurrido un error al actualizar el FTP: '.$result[2]; 
        }

    }else{

        $errorMessage['state'] = 'danger';
        $errorMessage['message'] = 'Ha ocurrido un error al actualizar el FTP: '. $form->getErrorsAsString();

    } 

    $app['session']->getFlashBag()->add($errorMessage['state'], $errorMessage['message'] );

    return $app->redirect($app['url_generator']->generate('admin'));

})->bind('update');

//FTP delete
$backend->post('/', function() use ($app){

    $form = $app['form_delete'];

    $form->bind($app['request']);

    if($form->isValid()){

        //Eliminar el ftp
        $result = $app['database']->deleteFtp($form->getData()['id']);

        if($result[0] == '00000'){

            $errorMessage['state'] = 'success';
            $errorMessage['message'] = 'FTP eliminado satisfactoriamente.';

        }else{
           
          $errorMessage['state'] = 'danger';
          $errorMessage['message'] = 'Ha ocurrido un error al eliminar el FTP: '.$result[2]; 
        }

    }else{ 

        $errorMessage['state'] = 'danger';
        $errorMessage['message'] = 'Ha ocurrido un error al eliminar el FTP: '. $form->getErrorsAsString();

    } 

    $app['session']->getFlashBag()->add($errorMessage['state'], $errorMessage['message'] );

    return $app->redirect($app['url_generator']->generate('admin'));   

})->bind('delete');

//Optener los datos de un ftp
$app->post('/get-data', function() use($app){

    $id = $app['request']->get('id');
    $data = $app['database']->getFtp($id);

    if(!$data){
        $result['success'] = false;
        $result['message'] = 'No se ha podido encontrar el registro.';
    }else{
        $result['success'] = true;
        $result['data'] = $data;

    }

    return $app->json($result);

})->bind('get_data');

/**
 * Acción para escanear un ftp
 */
$backend->get('/ftp/scan/', function() use ($app){

    return $app->json($app['ftpindexer']->scan(1));


})->bind('scan');

/**
 * Acción para escanear todos los ftps activos
 */
$backend->get('/ftp/scan/all', function() use ($app){

    return $app->json($app['ftpindexer']->scanAll());

})->bind('scan_all');

//Logout
$backend->get('/logout', function () use ($app) {

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
        ->add('direccion_ip', 'text', array(
            'constraints' => new Assert\Ip()
        ))
        ->add('activo', 'checkbox' , array('required' => false ))
        ->add('user', 'text', array(
            'constraints' => new Assert\NotBlank()
            ))
        ->add('pass', 'password', array(
            'constraints' => new Assert\NotBlank()    
            ))
     ->getForm();

     return $form;
};

//Formulario para eliminar ftp
$app['form_delete'] = function() use ($app){

    $form = $app['form.factory']->createBuilder('form')
        ->setMethod('DELETE')
        ->add('id', 'hidden')
    ->getForm();

    return $form;
};

return $backend;