<?php

namespace Kirby\Cli\Command;

use F;
use Dir;
use RuntimeException;

use Kirby\Cli\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class Uninstall extends Command {

  protected function configure() {
    $this->setName('uninstall')
         ->setDescription('Removes the Kirby installation');
  }

  protected function execute(InputInterface $input, OutputInterface $output) {

    if($this->isInstalled() === false) {
      throw new RuntimeException('Invalid Kirby installation');
    }

    $helper   = $this->getHelper('question');
    $question = new ConfirmationQuestion('<info>Do you really want to uninstall Kirby? (y/n)</info>' . PHP_EOL . 'leave blank to cancel: ', false);      
    
    if($helper->ask($input, $output, $question)) {

      // load kirby
      $this->bootstrap();

      f::remove($this->dir() . DS . 'index.php');
      f::remove($this->dir() . DS . '.htaccess');
      f::remove($this->dir() . DS . '.gitignore');
      f::remove($this->dir() . DS . 'license.md');
      f::remove($this->dir() . DS . 'readme.md');

      dir::remove($this->dir() . DS . 'kirby');
      dir::remove($this->dir() . DS . 'panel');
      dir::remove($this->dir() . DS . 'thumbs');

      $output->writeln('<comment>Kirby has been uninstalled!</comment>');
      $output->writeln('');

    }

  }

}