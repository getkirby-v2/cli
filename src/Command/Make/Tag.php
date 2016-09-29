<?php

namespace Kirby\Cli\Command\Make;

use F;
use Str;

use Kirby\Cli\Command\Make;

class Tag extends Make {

  protected $what      = 'tag';
  protected $info      = 'Creates a new Kirbytext tag';
  protected $help      = 'Sets the name of the tag';
  protected $dest      = 'site/tags';
  protected $extension = 'php';

  protected function copy() {
    $tag = f::read($this->template());
    $tag = str::template($tag, [
      'name' => $this->name()
    ]);

    // copy the tag template to the tags directory
    f::write($this->file(), $tag);    
  }

}