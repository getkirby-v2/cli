<?php

namespace Kirby\Cli\Command\Plugin;

use Dir;
use RuntimeException;

use Kirby\Cli\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class Uninstall extends Command {

  protected function configure() {
    $this->setName('plugin:uninstall')
         ->setDescription('Removes a Kirby plugin')
         ->addArgument('name', InputArgument::OPTIONAL, 'Plugin name');
  }

  protected function execute(InputInterface $input, OutputInterface $output) {

    if($this->isInstalled() === false) {
      throw new RuntimeException('Invalid Kirby installation');
    }

    if($plugin = $input->getArgument('name')) {
      $this->uninstall($plugin, $output);
    } else {

      $plugins = array_keys($this->kirby()->plugins());
      
      if(empty($plugins)) {
        $output->writeln('<info>There are no installed plugins</info>');
        $output->writeln('');
        return;
      }

      $helper   = $this->getHelper('question');
      $question = new ChoiceQuestion('<info>Which plugin do you want to uninstall?</info>', $plugins, 0);      
      $plugin   = $helper->ask($input, $output, $question);

      // add some space
      $output->writeln('');

      $this->uninstall($plugin, $output);

    }

  }

  protected function uninstall($plugin, $output) {

    $root = $this->kirby()->roots()->plugins() . DS . $plugin;

    dir::remove($root);

    $output->writeln('<comment>The "' . $plugin . '" plugin has been removed!</comment>');
    $output->writeln('');

  }



}