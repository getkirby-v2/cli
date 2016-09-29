<?php

namespace Kirby\Cli\Command\Delete;

use F;
use Dir;

use Kirby\Cli\Command\Delete;

class Tag extends Delete {

  protected $type        = 'tag';
  protected $description = 'Deletes a tag';

  protected function delete($name, $output) {

    f::remove($this->root() . DS . $name . '.php');      

    $output->writeln('<comment>The "' . $name . '" tag has been deleted!</comment>');
    $output->writeln('');

  } 

}