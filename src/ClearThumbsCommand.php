<?php

namespace Kirby\Cli;

use Dir;
use RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ClearThumbsCommand extends BaseCommand {

  protected function configure() {
    $this->setName('clear:thumbs')
         ->setDescription('Flushes the thumbs folder');
  }

  protected function execute(InputInterface $input, OutputInterface $output) {

    if(!$this->isInstalled()) {
      throw new RuntimeException('Invalid Kirby installation');
    }

    $this->bootstrap();

    // empty the cache directory
    dir::clean(getcwd() . '/thumbs');

    $output->writeln('<comment>The thumbs folder has been emptied</comment>');

  }

}