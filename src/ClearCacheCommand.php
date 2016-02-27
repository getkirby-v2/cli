<?php

namespace Kirby\Cli;

use Dir;
use RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ClearCacheCommand extends BaseCommand {

  protected function configure() {
    $this->setName('clear:cache')
         ->setDescription('Flushes the cache');
  }

  protected function execute(InputInterface $input, OutputInterface $output) {

    if(!$this->isInstalled()) {
      throw new RuntimeException('Invalid Kirby installation');
    }

    $this->bootstrap();

    // empty the cache directory
    dir::clean(getcwd() . '/site/cache');

    $output->writeln('<comment>The cache folder has been emptied</comment>');

  }

}