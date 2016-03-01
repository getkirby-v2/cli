<?php

namespace Kirby\Cli;

use RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class InstallCoreCommand extends BaseCommand {

  protected function configure() {
    $this->setName('install:core')
         ->setDescription('Installs the Kirby core');
  }

  protected function execute(InputInterface $input, OutputInterface $output) {

    $dir = getcwd();

    $this->filesystem([
      'local' => $dir,
      'kirby' => $dir
    ]);

    if(count($this->filesystem->listContents('kirby://kirby')) !== 0) {
      throw new RuntimeException('The kirby folder already exists and is not an empty directory.');
    }

    $output->writeln('<info>Installing the core...</info>');
    $output->writeln('<info></info>');

    // download the kit zip
    $zip = $this->download()->kit();

    // start to unzip the kit file
    $this->unzip()->start($zip, 'kirby');

    // delete the temporary zip file
    $this->filesystem->delete('local://'. basename($zip));

    // yay, everything is setup
    $output->writeln('<comment>The core is installed!</comment>');

  }

}