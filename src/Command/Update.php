<?php

namespace Kirby\Cli\Command;

use RuntimeException;

use Kirby\Cli\Command;
use Kirby\Cli\Util;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Update extends Command {

  protected function configure() {
    $this->setName('update')
         ->setDescription('Updates a Kirby CMS installation')
         ->addOption('dev', null, InputOption::VALUE_NONE, 'Set to update to the developer preview');
  }

  protected function execute(InputInterface $input, OutputInterface $output) {

    if($this->isInstalled() === false) {
      throw new RuntimeException('There seems to be no valid Kirby installation in this folder!');
    }

    $output->writeln('<info>Updating Kirby...</info>');
    $output->writeln('');

    // check if the panel is installed at all
    $hasPanel = is_dir($this->dir() . '/panel');

    // start updating the core
    $output->writeln('Updating the core...');

    // remove the old folder
    util::remove($this->dir() . '/kirby');

    // update the core
    $this->install([
      'repo'    => 'getkirby/kirby', 
      'branch'  => $input->getOption('dev') ? 'develop' : 'master',
      'path'    => $this->dir() . '/kirby', 
      'output'  => $output
    ]);

    // still has the old toolkit submodule
    if(is_dir($this->dir() . '/kirby/toolkit')) {

      // start updating the toolkit
      $output->writeln('Updating the toolkit...');

      // remove the toolkit folder first
      util::remove($this->dir() . '/kirby/toolkit');

      // update the toolkit
      $this->install([
        'repo'    => 'getkirby/toolkit', 
        'branch'  => $input->getOption('dev') ? 'develop' : 'master',
        'path'    => $this->dir() . '/kirby/toolkit', 
        'output'  => $output
      ]);

    }    

    if($hasPanel) {

      // start updating the panel
      $output->writeln('Updating the panel...');

      // remove the old panel folder first
      util::remove($this->dir() . '/panel');

      // update the panel
      $this->install([
        'repo'    => 'getkirby/panel', 
        'branch'  => $input->getOption('dev') ? 'develop' : 'master',
        'path'    => $this->dir() . '/panel', 
        'output'  => $output
      ]);

    }

    $output->writeln('<comment>Kirby has been updated to: ' . $this->version() . '!</comment>');
    $output->writeln('');



  }

}