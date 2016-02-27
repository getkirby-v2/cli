<?php

namespace Kirby\Cli;

class MakeControllerCommand extends MakeCommand {

  protected $what      = 'controller';
  protected $info      = 'Creates a new controller';
  protected $help      = 'Sets the name of the controller';
  protected $dir       = 'site/controllers';
  protected $extension = 'php';

}