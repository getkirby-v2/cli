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
use Symfony\Component\Console\Question\ConfirmationQuestion;

class PluginCommand extends BaseCommand {

  protected $input;
  protected $output;
  protected $path;
  protected $zip;
  protected $tmp;
  protected $info;

  protected function execute(InputInterface $input, OutputInterface $output) {

    $output->writeln('<info>Downloading Plugin...</info>');
    $output->writeln('<info></info>');

    $this->input  = $input;
    $this->output = $output;

    // check for a valid kirby installation
    if(!$this->isInstalled()) {
      throw new RuntimeException('Invalid Kirby installation');
    }

    // load kirby
    $this->bootstrap();

  }

  protected function repo() {
    
    // shortcut for official getkirby-plugins
    if(preg_match('!^[a-z0-9_-]+$!i', $this->path)) {
      $path = 'getkirby-plugins/' . $this->path;
    } else {
      $path = $this->path;
    }

    return 'https://github.com/' . $path;

  }

  /**
   * Download and extract the plugin
   */
  protected function fetch() {

    $this->path = $this->input->getArgument('path');
    $this->zip  = $this->download()->plugin($this->repo());
    $this->tmp  = $this->dir() . '/' . f::name($this->zip);

    $this->filesystem([
      'kirby' => $this->tmp
    ]);

    // start to unzip the kit file
    $this->unzip()->start($this->zip);

    // remove the zip
    f::remove($this->zip);    

    // grab the info from the plugin
    $this->info();
  
  }

  /**
   * Read the plugin package file and 
   * return the info as array
   */
  protected function info() {

    $this->output->writeln('<info>Reading package info...</info>');
    $this->output->writeln('<info></info>');

    $this->info = data::read($this->tmp . '/package.json');

    // get the extension type
    if(!isset($this->info['type'])) {
      throw new RuntimeException('Invalid Kirby Plugin Type');
    } 

    // check for a valid type
    if(!in_array($this->type(), ['plugin', 'field', 'tag'])) {
      throw new RuntimeException('Invalid Kirby Plugin Type: "' . $this->type() . '"');
    }

    if(!isset($this->info['name'])) {
      throw new RuntimeException('Invalid Kirby Plugin Name');
    }

    $this->output->writeln('<info>Discovered a ' . $this->type() . ' with the name "' . $this->name() . '"</info>');
    $this->output->writeln('<info></info>');

    $this->output->writeln('<info>Description: ' . "\t" . $this->description() . '</info>');
    $this->output->writeln('<info>Repository: ' . "\t" . $this->repo() . '</info>');
    $this->output->writeln('<info>Version: ' . "\t" . $this->version() . '</info>');
    $this->output->writeln('<info>Author: ' . "\t" . $this->author() . '</info>');
    $this->output->writeln('<info>License: ' . "\t" . $this->license() . '</info>');
    $this->output->writeln('<info></info>');

  }

  /**
   * Get the plugin description
   */
  protected function description() {
    return isset($this->info['description']) ? $this->info['description'] : '-';
  }

  /**
   * Get the current version number
   */
  protected function version() {
    return isset($this->info['version']) ? $this->info['version'] : '-';
  }

  /**
   * Get the plugin author
   */
  protected function author() {
    return isset($this->info['author']) ? $this->info['author'] : 'unknown';
  }

  /**
   * Get the license   
   */
  protected function license() {
    return isset($this->info['license']) ? $this->info['license'] : 'unknown';
  }

  /**
   * Return the clear name of the plugin
   */
  protected function name() {
    return basename($this->info['name']);
  }

  /**
   * Return the plugin type
   */
  protected function type() {
    return str_replace('kirby-', '', $this->info['type']);    
  }

  /**
   * Remove the tmp directory
   */
  protected function cleanUp() {
    dir::remove($this->tmp);    
  }

  protected function source() {

    switch($this->type()) {
      case 'plugin':
      case 'field':
        return $this->tmp;
        break;
      case 'tag':
        return $this->tmp . '/' . $this->name() . '.php';
        break;
    }

  }

  protected function destination() {

    switch($this->type()) {
      case 'plugin':
        return $this->dir() . '/site/plugins/' . $this->name();
        break;
      case 'field':
        return $this->dir() . '/site/fields/' . $this->name();
        break;
      case 'tag':
        return $this->dir() . '/site/tags/' . $this->name() . '.php';
        break;
    }

  }

  protected function sourceExists() {
    return (file_exists($this->source()) or is_dir($this->source()));    
  }

  protected function pluginExists() {
    return (file_exists($this->destination()) or is_dir($this->destination()));
  }

  protected function prepare() {

    // check for an existing field with the same name
    if(!$this->sourceExists()) {
      throw new RuntimeException('The correct source for the plugin is missing');
    }

    dir::make(dirname($this->destination()));
  
  }

  /**
   * Move the source to the final destination
   */
  protected function move() {

    $exists  = $this->pluginExists();
    $error   = $exists ? 'The plugin could not be updated' : 'The plugin could not be installed';
    $success = $exists ? 'The plugin has been updated to version:' : 'The plugin has been installed at version:';

    if($exists) {
      $this->output->writeln('<info>Updating plugin...</info>');      
    } else {
      $this->output->writeln('<info>Installing plugin...</info>');      
    }

    $this->output->writeln('<info></info>');

    $this->prepare();

    $src  = $this->source();
    $dest = $this->destination();

    // overwriting means having to clean the plugin first
    if(is_dir($dest)) {
      if(!dir::remove($dest)) {
        throw new RuntimeException('The old plugin could not be removed before the update');
      }
    }

    if(is_file($src)) {
      if(!f::move($src, $dest)) {
        throw new RuntimeException($error);            
      }
    } else if(is_dir($src)) {
      if(!dir::move($src, $dest)) {
        throw new RuntimeException($error);            
      }
    } else {
      throw new RuntimeException('Invalid source');
    }

    $this->output->writeln('<comment>' . $success . ' "' . $this->version() . '"</comment>');

  }

  protected function confirm($message) {

    $helper   = $this->getHelper('question');
    $question = new ConfirmationQuestion($message . ' [y/n] ', false);

    return $helper->ask($this->input, $this->output, $question);

  }

}