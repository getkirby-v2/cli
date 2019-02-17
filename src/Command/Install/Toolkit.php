<?php

namespace Kirby\Cli\Command\Install;

use RuntimeException;

use Kirby\Cli\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Toolkit extends Command {

  protected function configure() {
    $this->setName('install:toolkit')
         ->setDescription('Installs the Kirby toolkit')
         ->addOption('dev', null, InputOption::VALUE_NONE, 'Set to download the dev version from the develop branch');
  }

  protected function execute(InputInterface $input, OutputInterface $output) {

    $this->install([
      'repo'    => 'getkirby-v2/toolkit',
      'branch'  => $input->getOption('dev') ? 'develop' : 'master',
      'path'    => $this->dir() . '/toolkit', 
      'output'  => $output,
      'success' => 'The toolkit is installed!',
    ]);

  }

}