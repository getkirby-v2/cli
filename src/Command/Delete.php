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
use Symfony\Component\Console\Question\ChoiceQuestion;

class Delete extends Command {

  protected $type = '';
  protected $description = 'Deletes a Kirby component';

  protected function configure() {
    $this->setName('delete:' . $this->type)
         ->setDescription($this->description)
         ->addArgument('name', InputArgument::OPTIONAL, 'The name of the ' . $this->type);
  }

  protected function execute(InputInterface $input, OutputInterface $output) {

    if($this->isInstalled() === false) {
      throw new RuntimeException('Invalid Kirby installation');
    }

    // bootstrap kirby, just in case
    $this->bootstrap();

    if($name = $input->getArgument('name')) {

      $helper   = $this->getHelper('question');
      $question = new ConfirmationQuestion('<info>Do you really want to delete the "' . $name . '" ' . $this->type . '? (y/n)</info>' . PHP_EOL . 'leave blank to cancel: ', false);      
      
      if($helper->ask($input, $output, $question)) {
        $this->delete($name, $output);
      }

    } else {

      $selection = $this->selection();

      if(empty($selection)) {
        $output->writeln('<info>There are no installed ' . $this->type . '</info>');
        $output->writeln('');
        return;
      }

      $helper   = $this->getHelper('question');
      $question = new ChoiceQuestion('<info>Which ' . $this->type . ' do you want to delete?</info>', $selection, 0);      
      $item     = $helper->ask($input, $output, $question);

      // add some space
      $output->writeln('');

      $this->delete($item, $output);

    }

  }

  protected function delete($name, $output) {
    return;
  }

  protected function root() {
    return $this->kirby()->roots()->site() . DS . $this->type . 's';
  }

  protected function selection() {
    return array_map(function($filename) {
      return f::name($filename);
    }, dir::read($this->root()));
  }

}