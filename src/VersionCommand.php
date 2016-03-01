<?php

namespace Kirby\Cli;

use Kirby;
use Toolkit;
use Panel;
use RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class VersionCommand extends BaseCommand {

  protected function configure() {
    $this->setName('version')
         ->setDescription('Prints the current versions of the core, the toolkit and the panel of your installation');
  }

  protected function execute(InputInterface $input, OutputInterface $output) {

    if(!$this->isInstalled()) {
      throw new RuntimeException('Invalid Kirby installation');
    }

    // bootstrap the core
    $this->bootstrap();

    // also bootstrap the panel
    require $this->dir() . '/panel/app/bootstrap.php';

    $output->writeln("<info>Core:\t\t" . kirby::version() . "</info>");
    $output->writeln("<info>Toolkit:\t" . toolkit::version() . "</info>");
    $output->writeln("<info>Panel:\t\t" . panel::version() . "</info>");

  }

}