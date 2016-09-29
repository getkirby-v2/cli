<?php

namespace Kirby\Cli\Command\Delete;

use F;
use Dir;

use Kirby\Cli\Command\Delete;

class Controller extends Delete {

  protected $type        = 'controller';
  protected $description = 'Deletes a controller';

  protected function delete($name, $output) {

    f::remove($this->root() . DS . $name . '.php');      

    $output->writeln('<comment>The "' . $name . '" controller has been deleted!</comment>');
    $output->writeln('');

  } 

}