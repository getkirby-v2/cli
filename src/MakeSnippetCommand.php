<?php

namespace Kirby\Cli;

class MakeSnippetCommand extends MakeCommand {

  protected $what      = 'snippet';
  protected $info      = 'Creates a new snippet';
  protected $help      = 'Sets the name of the snippet';
  protected $dest      = 'site/snippets';
  protected $extension = 'php';

}