<?php

namespace Kirby\Cli;

use RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class InstallHtaccessCommand extends BaseCommand {

  protected function configure() {
    $this->setName('install:htaccess')
         ->setDescription('Installs the default .htaccess in the current directory');
  }

  protected function execute(InputInterface $input, OutputInterface $output) {

    $file = getcwd() . '/.htaccess';
    $url  = 'https://raw.githubusercontent.com/getkirby/starterkit/master/.htaccess';

    if(file_exists($file)) {
      throw new RuntimeException('The .htaccess already exists and cannot be overwritten');
    }

    $output->writeln('<info>Installing .htaccess...</info>');
    $output->writeln('<info></info>');

    $this->download()->start($url, $file);

    // yay, everything is setup
    $output->writeln('<comment>The .htaccess is installed!</comment>');

  }

}