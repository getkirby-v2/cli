<?php

namespace Kirby\Cli\Command\Install;

use RuntimeException;

use Kirby\Cli\Command;
use Kirby\Cli\Util;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Htaccess extends Command {

  protected function configure() {
    $this->setName('install:htaccess')
         ->setDescription('Installs the default .htaccess in the current directory');
  }

  protected function execute(InputInterface $input, OutputInterface $output) {

    $file = $this->dir() . '/.htaccess';
    
    if(file_exists($file)) {
      throw new RuntimeException('The .htaccess file already exists. Please remove the old one it first.');
    }

    $download = util::download('https://raw.githubusercontent.com/getkirby-v2/starterkit/master/.htaccess');
    
    file_put_contents($file, $download);

    $output->writeln('');
    $output->writeln('<comment>The .htaccess has been installed!</comment>');
    $output->writeln('');

  }

}