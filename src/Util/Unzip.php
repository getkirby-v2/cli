<?php

namespace Kirby\Cli\Util;

use League\CLImate\CLImate;
use League\Flysystem\Filesystem;
use League\Flysystem\MountManager;
use League\Flysystem\ZipArchive\ZipArchiveAdapter;

class Unzip {

  protected $filesystem;
  protected $climate;

  public function __construct(MountManager $filesystem, Climate $climate) {
    $this->filesystem = $filesystem;
    $this->climate    = $climate;
  }

  public function start($file, $dest = null, $message = 'Extracting files') {

    $zip = new Filesystem(new ZipArchiveAdapter($file));    

    // add the zip file to the mount manager
    $this->filesystem->mountFilesystem('zip', $zip);

    // get all contents of the zip file
    $zipContents = $zip->listContents('./');

    if(count($zipContents) === 1) {
      $start = $zipContents[0]['path'];
    } else {
      $start = './';      
    }

    if(!is_null($dest)) {
      $start = $start . '/' . $dest;
      $dest  = rtrim($dest, '/') . '/';
    }

    $contents = $zip->listContents($start, true);
    $progress = $this->climate->progress()->total(count($contents));

    foreach($contents as $entry) {

      $path = str_replace($start . '/', '', $entry['path']);

      if($entry['type'] === 'dir') {
        $this->filesystem->createDir('kirby://' . $dest . $path);
      } else {
        $this->filesystem->put('kirby://' . $dest . $path, $this->filesystem->read('zip://' . $entry['path']));
      }

      $progress->advance(1, $message);

    }

  }

}