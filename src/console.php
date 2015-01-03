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
        // new InputOption('some-option', null, InputOption::VALUE_NONE, 'Some help'),
    ))
    ->setDescription('Indexa el contenido de los ftps')
    ->setHelp(<<<EOT
El comando <info>ftp:indexer</info> comienza el proceso de indexación del contenido de los ftps pasados como argumentos.
EOT
)
    ->setCode(function (InputInterface $input, OutputInterface $output) use ($app) {
        $output->writeln('Indexando contenido...');
    })
;

return $console;
