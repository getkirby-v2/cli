<?php

namespace Kirby\Cli\Command\Delete;

use F;
use Dir;

use Kirby\Cli\Command\Delete;

class Template extends Delete {

  protected $type        = 'template';
  protected $description = 'Deletes a template';

  protected function delete($name, $output) {

    f::remove($this->root() . DS . $name . '.php');      

    $output->writeln('<comment>The "' . $name . '" template has been deleted!</comment>');
    $output->writeln('');

  } 

}