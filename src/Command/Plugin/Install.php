<?php

namespace Kirby\Cli\Command\Plugin;

use RuntimeException;

use Kirby\Cli\Command\Plugin;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Install extends Plugin {

  protected function configure() {
    $this->setName('plugin:install')
         ->setDescription('Installs a new Kirby plugin from Github')
         ->addArgument('path', InputArgument::REQUIRED, 'Github path');
  }

  protected function execute(InputInterface $input, OutputInterface $output) {

    parent::execute($input, $output);

    // download and extract the plugin
    $this->fetch();

    // check for an existing plugin with 
    // the same name and avoid overwriting it
    if($this->pluginExists()) {
      if(!$this->confirm('The plugin is already installed. Do you want to update it?')) {
        $this->cleanUp();
        return;      
      }      
    } else {
      if(!$this->confirm('Do you want to install this plugin?')) {
        $this->cleanUp();
        return;      
      }
    }

    try {
      $this->move();
      $this->cleanUp();
    } catch(RuntimeException $e) {
      // make sure to clean up even after errors
      $this->cleanUp();
      throw $e;
    }

  }

}