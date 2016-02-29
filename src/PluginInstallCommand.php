<?php

namespace Kirby\Cli;

use F;
use Dir;
use Data;
use RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class PluginInstallCommand extends BaseCommand {

  protected function configure() {
    $this->setName('plugin:install')
         ->setDescription('Installs a new Kirby plugin from Github')
         ->addArgument('path', InputArgument::REQUIRED, 'Github path');
  }

  protected function execute(InputInterface $input, OutputInterface $output) {

    $output->writeln('<info>Downloading Plugin...</info>');
    $output->writeln('<info></info>');

    $this->bootstrap();

    $dir  = getcwd();
    $path = $input->getArgument('path');
    $zip  = $this->download()->plugin($path);
    $tmp  = $dir . '/' . f::name($zip);

    $this->filesystem([
      'kirby' => $tmp
    ]);

    // start to unzip the kit file
    $this->unzip()->start($zip);

    // remove the zip
    f::remove($zip);

    try {
      $this->install($dir, $tmp, $output);      
    } catch(RuntimeException $e) {
      // clean up
      dir::remove($tmp);
      throw $e;
    }

  }

  protected function install($dir, $tmp, $output) {

    $output->writeln('<info>Reading package info...</info>');
    $output->writeln('<info></info>');

    $info = data::read($tmp . '/package.json');

    // get the extension type
    if(!isset($info['type'])) {
      throw new RuntimeException('Invalid Kirby Plugin Type');
    } 

    if(!isset($info['name'])) {
      throw new RuntimeException('Invalid Kirby Plugin Name');
    }

    // get the real name of the plugin without namespace
    $name = basename($info['name']);
    $type = str_replace('kirby-', '', $info['type']);

    $output->writeln('<info>Discovered a ' . $type . ' with the name "' . $name . '"</info>');
    $output->writeln('<info></info>');

    switch($type) {
      case 'plugin':
        $output->writeln('<info>Installing plugin...</info>');
        $output->writeln('<info></info>');

        // where to store the plugin
        $dest = $dir . '/site/plugins/' . $name;

        // check for an existing plugin with the same name
        if(is_dir($dest)) {
          throw new RuntimeException('The plugin is already installed. Use kirby plugin:update to update an installed plugin');
        }

        // create the plugins folder
        dir::make(dirname($dest));

        // try to move the plugin
        if(!dir::move($tmp, $dest)) {
          throw new RuntimeException('The plugin could not be installed');
        }

        // setup the success message
        $message = 'The plugin has been installed';

        break;
      case 'field':
        $output->writeln('<info>Installing panel field...</info>');
        $output->writeln('<info></info>');

        // where to store the field
        $dest = $dir . '/site/fields/' . $name;

        // check for an existing field with the same name
        if(is_dir($dest)) {
          throw new RuntimeException('The field is already installed. Use kirby plugin:update to update an installed field');
        }

        // make the parent directory if it does not exist yet
        dir::make(dirname($dest));

        // try to move the field to the final destination
        if(!dir::move($tmp, $dest)) {
          throw new RuntimeException('The panel field could not be installed');            
        }

        // setup the success message
        $message = 'The panel field has been installed';

        break;
      case 'tag':
        $output->writeln('<info>Installing Kirby tag...</info>');
        $output->writeln('<info></info>');

        // which file to store
        $source = $tmp . '/' . $name . '.php';
        // where to store the tag file
        $dest   = $dir . '/site/tags/' . $name . '.php';

        // check for an existing source file
        if(!file_exists($source)) {
          throw new RuntimeException('Missing tag file with the name "' . $name . '.php" in Kirby tag plugin');
        }

        // check for an existing tag with the same name
        if(file_exists($dest)) {
          throw new RuntimeException('The tag is already installed. Use kirby plugin:update to update an installed tag');
        }

        // make the tags folder if it's not there yet
        dir::make(dirname($dest));

        // try to move the tag file
        if(!f::move($tmp . '/' . $name . '.php', $dest)) {
          throw new RuntimeException('The Kirby tag could not be installed');            
        }

        // setup the success message
        $message = 'The Kirby tag has been installed';

        break;
      default: 
        throw new RuntimeException('Unknown Kirby Plugin Type');
    }

    dir::remove($tmp);

    // yay, everything is setup
    $output->writeln('<comment>' . $message . '</comment>');

  }

}