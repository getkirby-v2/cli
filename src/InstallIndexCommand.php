<?php

namespace Kirby\Cli;

use RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class InstallIndexCommand extends BaseCommand {

  protected function configure() {
    $this->setName('install:index.php')
         ->setDescription('Installs the default index.php in the current directory');
  }

  protected function execute(InputInterface $input, OutputInterface $output) {

    $file = getcwd() . '/index.php';
    $url  = 'https://raw.githubusercontent.com/getkirby/starterkit/master/index.php';

    if(file_exists($file)) {
      throw new RuntimeException('The index.php already exists and cannot be overwritten');
    }

    $output->writeln('<info>Installing index.php...</info>');
    $output->writeln('<info></info>');

    $this->download()->start($url, $file);

    // yay, everything is setup
    $output->writeln('<comment>The index.php is installed!</comment>');

  }

}