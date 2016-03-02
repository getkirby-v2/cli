<?php

namespace Kirby\Cli;

use F;
use Str;

class MakePluginCommand extends MakeCommand {

  protected $what      = 'plugin';
  protected $info      = 'Creates a new plugin';
  protected $help      = 'Sets the name of the plugin';
  protected $dest      = 'site/plugins';
  protected $extension = 'php';

  protected function _template($what) {
    $template = f::read(__DIR__ . '/templates/plugin/' . $what);
    $template = str::template($template, [
      'name' => $this->name()
    ]);
    return $template;
  }

  protected function dest() {
    return parent::dest() . '/' . $this->name();
  }

  protected function exists() {
    return is_dir($this->dest());
  }

  protected function _file($what) {
    return $this->dest() . '/' . $what;
  }

  protected function copy() {
    f::write($this->_file($this->name() . '.php'), $this->_template('plugin.php'));
    f::write($this->_file('package.json'), $this->_template('package.json'));
    f::write($this->_file('readme.md'), $this->_template('readme.md'));
  }

}