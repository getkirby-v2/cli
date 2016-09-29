<?php

namespace Kirby\Cli\Command\Install;

use RuntimeException;

use Kirby\Cli\Command;
use Kirby\Cli\Util;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Index extends Command {

  protected function configure() {
    $this->setName('install:index.php')
         ->setDescription('Installs the default index.php in the current directory');
  }

  protected function execute(InputInterface $input, OutputInterface $output) {

    $file = $this->dir() . '/index.php';
    
    if(file_exists($file)) {
      throw new RuntimeException('The index.php file already exists. Please remove the old one it first.');
    }

    $download = util::download('https://raw.githubusercontent.com/getkirby/starterkit/master/index.php');
    
    file_put_contents($file, $download);

    $output->writeln('');
    $output->writeln('<comment>The index.php has been installed!</comment>');
    $output->writeln('');

  }

}