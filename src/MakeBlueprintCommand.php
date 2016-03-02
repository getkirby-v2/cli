<?php

namespace Kirby\Cli;

use Data;
use Dir;
use F;
use Str;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class MakeBlueprintCommand extends MakeCommand {

  protected $what      = 'blueprint';
  protected $info      = 'Creates a new blueprint';
  protected $help      = 'Sets the name of the blueprint';
  protected $dest      = 'site/blueprints';
  protected $extension = 'yml';

  protected function configure() {

    parent::configure();

    $this->addOption('bare', 'b', InputOption::VALUE_NONE, 'Set to create a bare blueprint, without running the blueprint builder');

  }

  protected function copy() {

    if($this->input->getOption('bare')) {

      $blueprint = f::read($this->template());
      $blueprint = str::template($blueprint, [
        'title' => ucfirst($this->name())
      ]);

      f::write($this->file(), $blueprint);

    } else {
      $this->questions();
    }

  }

  protected function exists() {
    return f::resolve(dirname($this->file()) . '/' . f::name($this->file()), ['php', 'yml', 'yaml']);
  }

  protected function questions() {

    $blueprint = [
      'title' => ucfirst($this->name()),
      'pages' => [
        'template' => true,
        'num'      => [
          'mode'    => 'default',
          'display' => null
        ],
        'max'   => null,
        'limit' => 20,
        'sort'  => null,
        'hide'  => false
      ],
      'files' => [
        'sortable' => false,
        'max'      => null,
        'hide'     => false,
        'sanitize' => true,
        'fields'   => null
      ],
      'fields' => [
        'title' => [
          'label' => 'Title',
          'type'  => 'title'
        ]
      ]
    ];

    $this->output->writeln('');
    $this->output->writeln('<info>General Settings:</info>');
    $this->output->writeln('');

    $helper   = $this->getHelper('question');
    $question = new Question('<comment>Please enter a title for the blueprint</comment>' . PHP_EOL . 'leave blank to use "' . ucfirst($this->name()) . '": ', ucfirst($this->name()));    
    $blueprint['title'] = $helper->ask($this->input, $this->output, $question);

    $this->output->writeln('');
    $this->output->writeln('<info>Page Settings:</info>');
    $this->output->writeln('');

    $question = new ConfirmationQuestion('<comment>Allow subpages (y/n)</comment>' . PHP_EOL . 'leave blank to set "y": ', true);

    if($helper->ask($this->input, $this->output, $question)) {

      // Allowed Templates
      $this->output->writeln('');

      $question = new ChoiceQuestion('<comment>Templates for subpages (i.e. 1,2,3)</comment>' . PHP_EOL . 'leave blank to set "all"' . PHP_EOL, $this->templates(), 0);
      $question->setMultiselect(true);
      
      $templates = $helper->ask($this->input, $this->output, $question);

      if(count($templates) == 1 and $templates[0] == 'all') {
        $blueprint['pages']['template'] = null;        
      } else {
        $blueprint['pages']['template'] = $templates;        
      }

      // Numbering mode
      $this->output->writeln('');

      $question = new ChoiceQuestion('<comment>Numbering mode for subpages</comment>' . PHP_EOL . 'leave blank to use "default"' . PHP_EOL, ['default', 'date', 'zero'], 0);
      $question->setErrorMessage('The numbering mode "%s" is invalid.');
      $blueprint['pages']['num']['mode'] = $helper->ask($this->input, $this->output, $question);

      // Sorting
      $this->output->writeln('');

      $question = new Question('<comment>Sort subpages (i.e. title desc)</comment>' . PHP_EOL . 'leave blank to use default: ', false);
      $blueprint['pages']['sort'] = $helper->ask($this->input, $this->output, $question);

      // Limit
      $this->output->writeln('');

      $question = new Question('<comment>Number of shown subpages in the sidebar</comment>' . PHP_EOL . 'leave blank to use default: ', 20);
      $blueprint['pages']['limit'] = $helper->ask($this->input, $this->output, $question, null);

      // Max subpages
      $this->output->writeln('');

      $question = new Question('<comment>Maximum number of allowed subpages</comment>' . PHP_EOL . 'leave blank to not set a limit: ', null);
      $blueprint['pages']['max'] = $helper->ask($this->input, $this->output, $question, null);

    } else {
      $blueprint['pages'] = false;
    }

    $this->output->writeln('');
    $this->output->writeln('<info>File Settings:</info>');
    $this->output->writeln('');

    $question = new ConfirmationQuestion('<comment>Allow files (y/n)</comment>' . PHP_EOL . 'leave blank to set "y": ', true);

    if($helper->ask($this->input, $this->output, $question)) {

      // Sortable files
      $this->output->writeln('');
  
      $question = new ConfirmationQuestion('<comment>Sortable files (y/n)</comment>' . PHP_EOL . 'leave blank to set "n": ', false);
      $blueprint['files']['sortable'] = $helper->ask($this->input, $this->output, $question);

      // Max file size
      $this->output->writeln('');
  
      $question = new Question('<comment>Maximum allowed file size (in kB)</comment>' . PHP_EOL . 'leave blank to not set a limit: ', null);
      $blueprint['files']['size'] = $helper->ask($this->input, $this->output, $question, null);

      // Max files
      $this->output->writeln('');

      $question = new Question('<comment>Maximum number of allowed files</comment>' . PHP_EOL . 'leave blank to not set a limit: ', null);
      $blueprint['files']['max'] = $helper->ask($this->input, $this->output, $question, null);

      // Max width
      $this->output->writeln('');

      $question = new Question('<comment>Maximum width of allowed images</comment>' . PHP_EOL . 'leave blank to not set a limit: ', null);
      $blueprint['files']['width'] = $helper->ask($this->input, $this->output, $question, null);

      // Max height
      $this->output->writeln('');
  
      $question = new Question('<comment>Maximum height of allowed images</comment>' . PHP_EOL . 'leave blank to not set a limit: ', null);
      $blueprint['files']['height'] = $helper->ask($this->input, $this->output, $question, null);

    } else {
      $blueprint['files'] = false;
    }    

    // create the blueprint with all the entered data
    data::write($this->file(), $blueprint);    
 
  }

  protected function info() {

    $this->output->writeln('');
    $this->output->writeln('');
    $this->output->writeln('<info>The "' . $this->name() . '" blueprint has been created!</info>');
    $this->output->writeln('');
    $this->output->writeln('Don\'t forget to define fields in the final blueprint file:');
    $this->output->writeln('<comment>/site/blueprints/' . basename($this->file()) . '</comment>');
    $this->output->writeln('');
    $this->output->writeln('You can read more about fields and other options in the docs:');
    $this->output->writeln('<comment>https://getkirby.com/docs/panel/blueprints</comment>');
    $this->output->writeln('');
    $this->output->writeln('');

  }

  protected function templates() {

    $templates = ['all'];

    foreach(dir::read($this->dir() . '/site/blueprints') as $file) {
      if(!in_array(f::extension($file), ['php', 'yml', 'yaml'])) continue;
      $name = f::name($file);
      if(!in_array($name, ['error', 'site', 'home'])) {
        $templates[] = $name;
      }
    }

    return $templates;

  }

}