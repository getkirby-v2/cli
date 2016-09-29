<?php

namespace Kirby\Cli\Command\Make;

use Kirby\Cli\Command\Make;

class Controller extends Make {

  protected $what      = 'controller';
  protected $info      = 'Creates a new controller';
  protected $help      = 'Sets the name of the controller';
  protected $dest      = 'site/controllers';
  protected $extension = 'php';

}