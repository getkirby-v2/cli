<?php

namespace Kirby\Cli\Command\Clear;

use RuntimeException;

use Kirby\Cli\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Cache extends Command {

  protected function configure() {
    $this->setName('clear:cache')
         ->setDescription('Flushes the cache');
  }

  protected function execute(InputInterface $input, OutputInterface $output) {

    if($this->isInstalled() === false) {
      throw new RuntimeException('Invalid Kirby installation');
    }
  
    $this->kirby()->cache()->flush();

    $output->writeln('<comment>The cache folder has been emptied</comment>');
    $output->writeln('');

  }

}