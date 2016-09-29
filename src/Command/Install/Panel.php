<?php

namespace Kirby\Cli\Command\Install;

use RuntimeException;

use Kirby\Cli\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Panel extends Command {

  protected function configure() {
    $this->setName('install:panel')
         ->setDescription('Installs the Kirby panel')
         ->addOption('dev', null, InputOption::VALUE_NONE, 'Set to download the dev version from the develop branch');
  }

  protected function execute(InputInterface $input, OutputInterface $output) {

    $this->install([
      'repo'    => 'getkirby/panel', 
      'branch'  => $input->getOption('dev') ? 'develop' : 'master',
      'path'    => $this->dir() . '/panel', 
      'output'  => $output,
      'success' => 'The panel is installed!',
    ]);

  }

}