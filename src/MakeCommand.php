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
  protected $dest;
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

    // check if the thing already exists
    if($this->exists()) {
      throw new RuntimeException('The ' . $this->what . ' exists and cannot be overwritten');
    }

    // make sure the directory exists
    dir::make($this->dest());

    $this->copy();    
    $this->info();

  }

  protected function exists() {
    return file_exists($this->file());
  }

  protected function dest() {
    return getcwd() . '/' . $this->dest;
  }

  protected function name() {
    return strtolower($this->input->getArgument('name'));    
  }

  protected function file() {
    return $this->dest() . '/' . $this->name() . '.' . $this->extension;
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