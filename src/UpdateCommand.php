<?php

namespace Kirby\Cli;

use RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateCommand extends BaseCommand {

  protected $zip;

  protected function configure() {
    $this->setName('update')
         ->setDescription('Updates a Kirby CMS installation');
  }

  protected function execute(InputInterface $input, OutputInterface $output) {

    $output->writeln('<info>Updating Kirby...</info>');
    $output->writeln('<info></info>');

    if(!$this->isInstalled()) {
      throw new RuntimeException('Invalid Kirby installation');
    }

    $dir = getcwd();

    $this->filesystem([
      'local' => $dir,
      'kirby' => $dir
    ]);

    // start the downloader and download the kit zip
    $this->zip = $this->download()->kit();
    
    // update the core
    $this->core();

    // update the panel if it exists
    if($this->filesystem->has('local://panel')) {
      $this->panel();      
    }

    // delete the temporary zip file
    $this->filesystem->delete('local://' . basename($this->zip));

    $output->writeln('<comment>Kirby has been updated to ' . $this->version() . '</comment>');

  }

  protected function core() {
    $this->component('kirby', 'Updating the core…');
  }

  protected function panel() {
    $this->component('panel', 'Updating the panel…');
  }

  protected function component($name, $message) {

    // delete and recreate the kirby folder
    $this->filesystem->deleteDir('local://' . $name);
    $this->filesystem->createDir('local://' . $name);

    // start to unzip the kit file
    $this->unzip()->start($this->zip, $name, $message);
    
  }

}