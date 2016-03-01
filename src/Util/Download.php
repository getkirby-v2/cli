<?php

namespace Kirby\Cli\Util;

use GuzzleHttp\Client;
use GuzzleHttp\Event\ProgressEvent;
use League\CLImate\CLImate;
use RuntimeException;

class Download {

  protected $climate;

  public function __construct(Climate $climate) {
    $this->climate = $climate;
  }

  public function start($url, $file, $message = null) {

    $client   = new Client();
    $progress = null;
    $request  = $client->createRequest('GET', $url, ['save_to' => $file]);
    $climate  = $this->climate;
    $message  = $message ?: 'Downloading Kirby from: ' . $url;

    $request->getEmitter()->on('progress', function(ProgressEvent $e) use (&$progress, $climate, $message) {

      if($e->downloadSize === 0) return;

      if($progress === null) {
        $progress = $climate->progress()->total($e->downloadSize);
      } else {
        $progress->current($e->downloaded, $message);
      }

    });

    $client->send($request);

  }

  public function kit($name = null) {

    if(empty($name)) {
      $name = 'starterkit';
    }

    $kits = ['starterkit', 'plainkit', 'langkit'];

    if(!in_array($name, $kits)) {
      throw new RuntimeException('Invalid kit: ' . $name);
    }

    $url  = 'https://github.com/getkirby/' . $name . '/archive/master.zip';
    $file = getcwd() . '/kirby-' . $name . '-' . md5(time() . uniqid()) . '.zip';

    $this->start($url, $file);

    return $file;

  }

  public function plugin($repo) {

    $url  = $repo . '/archive/master.zip';
    $file = getcwd() . '/kirby-plugin-' . md5(time() . uniqid()) . '.zip';

    $this->start($url, $file, 'Downloading plugin...');

    return $file;

  }

}