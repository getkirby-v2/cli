<?php

namespace Kirby\Cli\Command\Delete;

use F;
use Dir;

use Kirby\Cli\Command\Delete;

class Plugin extends Delete {

  protected $type        = 'plugin';
  protected $description = 'Deletes a plugin';

  protected function delete($name, $output) {

    dir::remove($this->root() . DS . $name);      

    $output->writeln('<comment>The "' . $name . '" plugin has been deleted!</comment>');
    $output->writeln('');

  } 

  protected function selection() {
    return array_keys($this->kirby()->plugins());
  }

}