<?php

namespace Kirby\Cli\Command;

use RuntimeException;

use Kirby\Cli\Command;
use Kirby\Cli\Util;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Install extends Command {

  protected function configure() {
    $this->setName('install')
         ->setDescription('Creates a new Kirby installation')
         ->addArgument('path', InputArgument::OPTIONAL, 'Directory to install into', $this->dir() . '/kirby')
         ->addOption('kit', 'k', InputOption::VALUE_OPTIONAL, 'Set to decide, which kit to install (starterkit, plainkit, langkit)', 'starterkit')
         ->addOption('dev', null, InputOption::VALUE_NONE, 'Set to download the dev version from the develop branch');
  }

  protected function execute(InputInterface $input, OutputInterface $output) {

    $kit  = $input->getOption('kit');
    $kits = ['starterkit', 'plainkit', 'langkit'];

    if(!in_array($kit, $kits)) {
      throw new RuntimeException('Invalid kit name: ' . $kit);
    }

    $output->writeln('');
    $output->writeln('Installing the ' . $kit . '...');

    if($input->getOption('dev')) {
      $this->dev($input, $output, $kit);
    } else {
      $this->install([
        'repo'    => 'getkirby/' . $kit, 
        'branch'  => 'master',
        'path'    => $input->getArgument('path'), 
        'output'  => $output,
        'success' => 'Kirby is installed!',
      ]);
    }

  }

  protected function dev($input, $output, $kit) {

    $path = $input->getArgument('path');

    $this->install([
      'repo'   => 'getkirby/' . $kit, 
      'branch' => 'master',
      'path'   => $path,
      'output' => $output,
    ]);

    util::remove(realpath($path) . '/panel');
    util::remove(realpath($path) . '/kirby');

    $output->writeln('Installing the core developer preview...');

    $this->install([
      'repo'   => 'getkirby/kirby', 
      'branch' => 'develop',
      'path'   => $path . '/kirby',
      'output' => $output
    ]);

    $output->writeln('Installing the panel developer preview...');

    $this->install([
      'repo'   => 'getkirby/panel', 
      'branch' => 'develop',
      'path'   => $path . '/panel',
      'output' => $output
    ]);

    $output->writeln('<comment>The developer preview has been installed!</comment>');
    $output->writeln('');

  }

}