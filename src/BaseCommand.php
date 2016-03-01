<?php

namespace Kirby\Cli;

use Kirby;
use Kirby\Cli\Util\Download;
use Kirby\Cli\Util\Unzip;
use League\CLImate\CLImate;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use League\Flysystem\MountManager;
use Symfony\Component\Console\Command\Command;

class BaseCommand extends Command {

  protected $climate;
  protected $filesystem;

  public function __construct() {
    $this->climate = new Climate();    
    parent::__construct();
  }

  protected function download() {
    return new Download($this->climate);
  }

  protected function filesystem($input) {

    $systems = [];

    foreach($input as $key => $dir) {
      $systems[$key] = new Filesystem(new Local($dir));
    }

    return $this->filesystem = new MountManager($systems);

  }

  protected function dir() {
    return getcwd();
  }

  protected function unzip() {
    // start to unzip the kit file
    return new Unzip($this->filesystem, $this->climate);    
  }

  protected function isInstalled() {
    return is_file($this->dir() . '/kirby/kirby.php');
  }

  protected function bootstrap() {
    require($this->dir() . '/kirby/bootstrap.php');    
  }

  protected function version() {
    $this->bootstrap();
    return Kirby::version();
  }

  protected function kirby() {
    $this->bootstrap();
    return new Kirby();
  }

}