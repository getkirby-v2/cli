<?php

namespace Kirby\Cli\Command\Clear;

use Dir;
use RuntimeException;

use Kirby\Cli\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Thumbs extends Command {

  protected function configure() {
    $this->setName('clear:thumbs')
         ->setDescription('Flushes the thumbs folder');
  }

  protected function execute(InputInterface $input, OutputInterface $output) {

    if($this->isInstalled() === false) {
      throw new RuntimeException('Invalid Kirby installation');
    }
  
    // get the thumbs folder and bootstrap kirby
    $thumbs = $this->kirby()->roots()->thumbs();

    // flush the thumbs folder
    dir::clean($thumbs);

    $output->writeln('<comment>The thumbs folder has been emptied</comment>');
    $output->writeln('');

  }

}