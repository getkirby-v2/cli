<?php

namespace Kirby\Cli\Command\Delete;

use F;
use Dir;

use Kirby\Cli\Command\Delete;

class Snippet extends Delete {

  protected $type        = 'snippet';
  protected $description = 'Deletes a snippet';

  protected function delete($name, $output) {

    f::remove($this->root() . DS . $name . '.php');      

    $output->writeln('<comment>The "' . $name . '" snippet has been deleted!</comment>');
    $output->writeln('');

  } 

}