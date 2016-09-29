<?php

namespace Kirby\Cli\Command\Delete;

use F;
use Dir;

use Kirby\Cli\Command\Delete;

class User extends Delete {

  protected $type        = 'user';
  protected $description = 'Deletes a user';

  protected function root() {
    return $this->kirby()->roots()->site() . DS . 'accounts';
  }

  protected function delete($name, $output) {

    f::remove($this->root() . DS . $name . '.php');      

    $output->writeln('<comment>The user "' . $name . '" has been deleted!</comment>');
    $output->writeln('');

  } 

  protected function selection() {
    return $this->site()->users()->pluck('username');
  }

}