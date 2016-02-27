<?php

namespace Kirby\Cli;

use F;
use Str;

class MakePluginCommand extends MakeCommand {

  protected $what      = 'plugin';
  protected $info      = 'Creates a new plugin';
  protected $help      = 'Sets the name of the plugin';
  protected $dir       = 'site/plugins';
  protected $extension = 'php';

  protected function dir() {
    return parent::dir() . '/' . $this->name();
  }

}