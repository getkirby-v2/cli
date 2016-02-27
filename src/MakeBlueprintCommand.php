<?php

namespace Kirby\Cli;

use F;
use Str;

class MakeBlueprintCommand extends MakeCommand {

  protected $what      = 'blueprint';
  protected $info      = 'Creates a new blueprint';
  protected $help      = 'Sets the name of the blueprint';
  protected $dir       = 'site/blueprints';
  protected $extension = 'yml';

  protected function copy() {
    $blueprint = f::read($this->template());
    $blueprint = str::template($blueprint, [
      'title' => ucfirst($this->name())
    ]);

    // copy the controller template to the controller directory
    f::write($this->file(), $blueprint);    
  }

}