<?php

namespace Kirby\Cli;

use Dir;
use F;
use RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MakeCommand extends BaseCommand {

  protected $input;
  protected $output;
  protected $what;
  protected $info; 
  protected $help;
  protected $dir;
  protected $extension;

  protected function configure() {
    $this->setName('make:' . $this->what)
          ->addArgument(
            'name',
            InputArgument::REQUIRED,
            $this->help
          )
         ->setDescription($this->info);
  }

  protected function execute(InputInterface $input, OutputInterface $output) {

    $this->input  = $input;
    $this->output = $output;

    if(!$this->isInstalled()) {
      throw new RuntimeException('Invalid Kirby installation');
    }

    $this->bootstrap();

    // make sure the directory exists
    dir::make($this->dir());

    // check if the thing already exists
    if(file_exists($this->file())) {
      throw new RuntimeException('The ' . $this->what . ' exists and cannot be overwritten');
    }

    $this->copy();    
    $this->info();

  }

  protected function dir() {
    return getcwd() . '/' . $this->dir;
  }

  protected function name() {
    return strtolower($this->input->getArgument('name'));    
  }

  protected function file() {
    return $this->dir() . '/' . $this->name() . '.' . $this->extension;
  }

  protected function template() {
    return __DIR__ . '/templates/' . $this->what . '.' . $this->extension;
  }

  protected function copy() {
    // copy the controller template to the controller directory
    f::copy($this->template(), $this->file());    
  }

  protected function info() {
    $this->output->writeln('<comment>The "' . $this->name() . '" ' . $this->what . ' has been created</comment>');    
  }

}