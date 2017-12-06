<?php

namespace Kirby\Cli;

use Kirby;
use RuntimeException;
use Kirby\Cli\Util;

class Command extends \Symfony\Component\Console\Command\Command {

  protected function dir() {
    return getcwd();
  }

  protected function tmp($filename) {
    //make it invisible by prepending a dot
    return rtrim(getcwd(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . '.' . ltrim($filename, DIRECTORY_SEPARATOR);
  }

  protected function checkPath($path) {

    if(count(glob($path . '/*')) !== 0) {
      throw new RuntimeException('The folder is not empty: ' . realpath($path));
    }

    if(is_dir($path))  {
      throw new RuntimeException('The folder exists and cannot be overwritten: ' . realpath($path));
    }

  }

  protected function download($params) {

    $options = array_merge([
      'repo'   => 'getkirby/starterkit',
      'branch' => 'master',
      'zip'    => null,
      'output' => null
    ], $params);

    extract($options);

    if(!$zip) {
      throw new RuntimeException('Please provide a zip file');
    }

    // build the download url
    $url = 'https://github.com/' . $repo . '/archive/' . $branch . '.zip';

    // generate some usable output
    if($output) {
      $output->writeln('<info>Downloading from: ' . $url . '</info>');
    }

    // send the remote request
    $download = util::download($url, function($resource, $total, $downloaded) use($output) {

      if(!$output) return null;

      if($downloaded && $total) {          
        $output->write('Downloaded: ' . round($downloaded / $total, 2) * 100 . "%\r");          
      }

    });

    // write the result to the disk
    file_put_contents($zip, $download);

  }

  protected function unzip($zip, $path) {
  
    // build the temporary folder path    
    $tmp = $this->tmp(basename($zip, '.zip'));

    // extract the zip file
    util::unzip($zip, $tmp);

    // get the list of directories within our tmp folder 
    $dirs = glob($tmp . '/*');

    // get the source directory from the tmp folder      
    if(isset($dirs[0]) && is_dir($dirs[0])) {
      $source = $dirs[0];
    } else {
      throw new RuntimeException('The source directory could not be found');
    }

    // create the folder if it does not exist yet
    if(!is_dir($path)) mkdir($path);

    // extract the content of the directory to the final path
    foreach((array)array_diff(scandir($source), ['.', '..']) as $name) {    
      if(!rename($source . '/' . $name, $path . '/' . $name)) {
        throw new RuntimeException($name . ' could not be copied');
      }
    }

    // remove the zip file
    util::remove($zip);

    // remove the temporary folder
    util::remove($tmp);
    
  }

  protected function install($params = []) {

    $options = array_merge([
      'repo'    => 'getkirby/starterkit',
      'branch'  => 'master',
      'path'    => null,
      'output'  => null,
      'success' => 'Done!'
    ], $params);

    // check for a valid path
    $this->checkPath($options['path']);
      
    // create the file name for the temporary zip file
    $zip = $this->tmp('kirby-' . str_replace('/', '-', $options['repo']) . '-' . uniqid() . '.zip');

    // download the file
    $this->download([
      'repo'   => $options['repo'],
      'branch' => $options['branch'],
      'zip'    => $zip,
      'output' => $options['output'],
    ]);

    // unzip the file
    $this->unzip($zip, $options['path']);

    // yay, everything is setup
    if($options['output'] && $options['success']) {
      $options['output']->writeln('');
      $options['output']->writeln('<comment>' . $options['success'] . '</comment>');
      $options['output']->writeln('');
    }

  }

  protected function isInstalled() {
    return is_file($this->dir() . '/kirby/kirby.php');
  }

  protected function bootstrap() {
    require_once $this->dir() . '/kirby/bootstrap.php';    
  }

  protected function version() {
    $this->bootstrap();
    return Kirby::version();
  }

  protected function kirby() {

    $this->bootstrap();

    // load the site to init all important
    // extensions and dependencies
    kirby()->site();

    return kirby();

  }

  protected function site() {
    return $this->kirby()->site();
  }

}