<?php

namespace Kirby\Cli;

use RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class InstallCommand extends BaseCommand {

  protected function configure() {

    $this->setName('install')
         ->setDescription('Creates a new Kirby installation')
         ->addArgument('path', InputArgument::OPTIONAL, 'Directory to install into', 'kirby')
         ->addOption('nightly', null, InputOption::VALUE_NONE, 'If set, will install the nightly build');

  }

  protected function execute(InputInterface $input, OutputInterface $output) {

    $dir = getcwd();

    $this->filesystem([
      'local' => $dir,
      'kirby' => $dir . '/' . $input->getArgument('path')
    ]);

    if(count($this->filesystem->listContents('kirby://')) !== 0) {
      throw new RuntimeException('Destination path "' . $input->getArgument('path') . '" already exists and is not an empty directory.');
    }

    $output->writeln('<info>Installing Kirby...</info>');
    $output->writeln('<info></info>');

    // download the kit zip
    $zip = $this->download()->kit($nightly ? 'nightly' : false);

    // start to unzip the kit file
    $this->unzip()->start($zip);

    // delete the temporary zip file
    $this->filesystem->delete('local://'. basename($zip));

    // yay, everything is setup
    $output->writeln('<comment>Kirby is installed!</comment>');

  }

}