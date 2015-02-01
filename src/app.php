<?php

use Silex\Application;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\SecurityServiceProvider;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use \DatabaseHandler;
use \FtpIndexer;

$app = new Application();

$app->register(new UrlGeneratorServiceProvider());
$app->register(new FormServiceProvider());
$app->register(new ValidatorServiceProvider());
$app->register(new TranslationServiceProvider(), array(
    'locale_fallback' => 'es'
    )
);
$app->register(new ServiceControllerServiceProvider());
$app->register(new TwigServiceProvider());
$app->register(new SecurityServiceProvider());
$app->register(new SessionServiceProvider());

/**
 * Servicio para manipular la base de datos
 */
$app['database'] = $app->share(function() use ($app){
	return new DatabaseHandler($app['db.options']['dsn'], $app['db.options']['user'], $app['db.options']['pass']);
});

$app['ftpindexer'] = $app->share(function() use ($app){
    return new FtpIndexer($app['database']);
});

$app->register(new TwigServiceProvider(), array(
    'twig.path'    => array(__DIR__.'/../templates'),
    // descomenta esta línea para activar la cache de Twig
    'twig.options' => array('cache' => __DIR__.'/../cache/twig'),

));

$app['twig'] = $app->share($app->extend('twig', function($twig, $app) {
    // add custom globals, filters, tags, ...
    
    $twig->addFilter('bytesToHRF', new \Twig_Filter_Function('bytesToHRF'));

    /**
     * Convierte bytes a la unidad indicada
     * @param  [type] $bytes [description]
     * @param  string $unit  [description]
     * @return [type]        [description]
     */
    function bytesToHRF($bytes,$unit=''){
        
        $units['TB']=1099511627776;
        $units['GB']=1073741824;
        $units['MB']=1048576;
        $units['KB']=1024;
        $units['B']=1;
        
        reset($units);
        
        while(list($key,$value)=each($units)) {
            if($key==$unit) {
                $bytes=sprintf('%01.2f',$bytes/$value);
                return $bytes." ".$key;;
            }
            if($bytes>$value && empty($unit)) {
                $bytes=sprintf('%01.2f',$bytes/$value);
                return $bytes." ".$key;
            }
        }

        return $bytes;
    }

    return $twig;
}));

// configuración de la seguridad
$app['security.encoder.digest'] = $app->share(function ($app) {
    // algoritmo SHA-1, con 1 iteración y sin codificar en base64
    return new MessageDigestPasswordEncoder('sha1', false, 1);
});

$app['security.firewalls'] = array(
    'admin' => array(
        'pattern' => '^/admin',
        'http'    => true,
        'users'   => array(
            // la contraseña sin codificar es "1234"
            'admin' => array('ROLE_ADMIN', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220'),
        ),
    ),
);

//Para listar los archivos de themes en web/css
$app['ListThemes'] = function () {
    $styles = array();
    $path = __DIR__."/../web/css/";
    if ($openeddir = opendir($path)) //Abro directorio
    {
        while (($obj = readdir($openeddir)) !== false)  //recorro su interior
            if(substr_count($obj, 'carola_site_'))      //buscando los archivos que contengan 'carola_site_'
            {
                $foo = str_replace('carola_site_', '', $obj); //elimino esa parte del nombre para obtener  'NOMBRE.css'
                $pos = strrpos(strtolower($foo), '.css');
                
                $styles[] = substr($foo, 0, $pos);  //añado a la lista el nombre excluyendo '.css'
            }                    
    }
    return $styles;
};

return $app;
