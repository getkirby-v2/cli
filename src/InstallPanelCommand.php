<?php

namespace Kirby\Cli;

use RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class InstallPanelCommand extends BaseCommand {

  protected function configure() {
    $this->setName('install:panel')
         ->setDescription('Installs the Kirby panel');
  }

  protected function execute(InputInterface $input, OutputInterface $output) {

    $dir = getcwd();

    $this->filesystem([
      'local' => $dir,
      'kirby' => $dir
    ]);

    if(count($this->filesystem->listContents('kirby://panel')) !== 0) {
      throw new RuntimeException('The panel folder already exists and is not an empty directory.');
    }

    $output->writeln('<info>Installing the Panel...</info>');
    $output->writeln('<info></info>');

    // download the kit zip
    $zip = $this->download()->kit();

    // start to unzip the kit file
    $this->unzip()->start($zip, 'panel');

    // delete the temporary zip file
    $this->filesystem->delete('local://'. basename($zip));

    // yay, everything is setup
    $output->writeln('<comment>The panel is installed!</comment>');

  }

}