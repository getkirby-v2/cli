<?php

namespace Kirby\Cli\Command\Delete;

use F;
use Dir;

use Kirby\Cli\Command\Delete;

class Blueprint extends Delete {

  protected $type        = 'blueprint';
  protected $description = 'Deletes a blueprint';

  protected function delete($name, $output) {

    if($file = f::resolve($this->root() . DS . $name, ['yaml', 'yml', 'php'])) {
      f::remove($file);      
    }

    $output->writeln('<comment>The "' . $name . '" blueprint has been deleted!</comment>');
    $output->writeln('');

  } 

}