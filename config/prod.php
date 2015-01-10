<?php

// configure your app for the production environment

$app['twig.path'] = array(__DIR__.'/../templates');
$app['twig.options'] = array('cache' => __DIR__.'/../var/cache/twig');

//configuracion de la conexión a la base de datos
$app['db.options'] =  array(
	'dsn' => 'mysql:host=localhost;dbname=carola;charset=UTF-8',
	'user' => 'root',
	'pass' => 'Cobra3000-'

);

