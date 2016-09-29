<?php

namespace Kirby\Cli\Command\Make;

use Kirby\Cli\Command\Make;

class Snippet extends Make {

  protected $what      = 'snippet';
  protected $info      = 'Creates a new snippet';
  protected $help      = 'Sets the name of the snippet';
  protected $dest      = 'site/snippets';
  protected $extension = 'php';

}