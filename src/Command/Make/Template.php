<?php

namespace Kirby\Cli\Command\Make;

use Kirby\Cli\Command\Make;

class Template extends Make {

  protected $what      = 'template';
  protected $info      = 'Creates a new template';
  protected $help      = 'Sets the name of the template';
  protected $dest      = 'site/templates';
  protected $extension = 'php';

}