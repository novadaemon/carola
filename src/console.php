<?php

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

$console = new Application('Carola', 'n/a');
$console->getDefinition()->addOption(new InputOption('--env', '-e', InputOption::VALUE_REQUIRED, 'The Environment name.', 'dev'));
$console->setDispatcher($app['dispatcher']);
$console
    ->register('ftp:indexer')
    ->setDefinition(array(
        new InputArgument(
			'ip',
			InputArgument::OPTIONAL,
			'Ip del ftp que se desea escanear. Si no se especifica ninguno se escanean todos.',
			'all'
			),
    ))
    ->setDescription('Indexa el contenido de los ftps')
    ->setHelp(<<<EOT
El comando <info>ftp:indexer</info> comienza el proceso de indexación del contenido de los ftps pasados como argumentos.
EOT
)
    ->setCode(function (InputInterface $input, OutputInterface $output) use ($app) {
        
        $output->writeln('Indexando contenido...');

        $ip = $input->getArgument('ip');

        if($ip == 'all'){
        	
        	$result = $app['ftpindexer']->scanAll();

        }else{

        	$ftp = $app['database']->getFtpByIp($ip);
            
            if(count($ftp) == 0) {
                $result = ['success' => false, 'message' => "El $ip no existe en la base de datos."];
            }else{
                $result = $app['ftpindexer']->scan($ftp[0]['id']);   
            }
        	
        }

        $output->writeln(var_dump($result));
    })
;

return $console;
