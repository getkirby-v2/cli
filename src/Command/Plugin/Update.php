<?php

namespace Kirby\Cli\Command\Plugin;

use RuntimeException;

use Kirby\Cli\Command\Plugin;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Update extends Plugin {

  protected function configure() {
    $this->setName('plugin:update')
         ->setDescription('Updates a Kirby plugin')
         ->addArgument('path', InputArgument::REQUIRED, 'Github path')
         ->addOption('branch', null, InputOption::VALUE_REQUIRED, 'Branch (master)', 'master');
  }

  protected function execute(InputInterface $input, OutputInterface $output) {

    parent::execute($input, $output);

    // download and extract the plugin
    $this->fetch();

    // check if the plugin exists
    if($this->pluginExists()) {
      if(!$this->confirm('Do you want to update this plugin?')) {
        $this->cleanUp();
        return;      
      }      
    } else {
      if(!$this->confirm('The plugin is not installed yet. Do you want to install it?')) {
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