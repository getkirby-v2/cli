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

  public function start($url, $file) {

    $client   = new Client();
    $progress = null;
    $request  = $client->createRequest('GET', $url, ['save_to' => $file]);
    $climate  = $this->climate;

    $request->getEmitter()->on('progress', function(ProgressEvent $e) use (&$progress, $climate, $nightly) {

      if($e->downloadSize === 0) return;

      if($progress === null) {
        $progress = $climate->progress()->total($e->downloadSize);
      } else {
        $progress->current($e->downloaded, 'Downloading latest Kirby ' . (($nightly) ? 'nightly' : 'release'));
      }

    });

    $client->send($request);

  }

  public function kit($name = null) {

    $url  = 'https://download.getkirby.com/' . $name;
    $file = getcwd() . '/kirby-' . $name . '-' . md5(time() . uniqid()) . '.zip';

    $this->start($url, $file);

    return $file;

  }

}