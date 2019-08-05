<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command as CommandAlias;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Process;

class RunServerCommand extends CommandAlias
{
    protected const MAIN_CONTROLLER = __DIR__ . '/../public/router.php';
    protected const ROOT_DIR = __DIR__ . '/../public';
    
    protected static $defaultName = 'server:run';
    
    protected function configure()
    {
        $this
            ->setDescription('Run a http testing server')
            ->setHelp('This command allows to run the http server for testing...');
        
        $this
            ->addOption('interface', null, InputArgument::OPTIONAL, 'Interface to listening', 'localhost')
            ->addOption('port', null, InputArgument::OPTIONAL, 'Port to listening', 3000);
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $date = date(DATE_RFC2822);
        $listeningRoute = "{$input->getOption('interface')}:{$input->getOption('port')}";
        
        
        $output->writeln("<info>Simple SSO toolkit 1.0.0 Test Server started at {$date}" . PHP_EOL . "Listenning on http://{$listeningRoute}</info>");
        $output->writeln('');
        
        $process = new Process(['php', '-S', "{$listeningRoute}", '-t', self::ROOT_DIR, self::MAIN_CONTROLLER]);
        $process->setTimeout(0);
        $process->start();
        
        foreach ($process as $type => $data) {
            if($type === Process::OUT) {
                echo $output->write("<info>{$data}</info>");
            }else{
                echo $output->write("<comment>{$data}</comment>");
            }
        }
    }
}