<?php

namespace Kirby\Api;

use GuzzleHttp\Client;

class Github {

  function __construct() {
    $this->client = new Client([
      'base_uri' => 'https://api.github.com'
    ]);
  }

  public function getTag($owner, $repo, $version = 'latest' ) {
    $response = $this->client->request('GET', 'repos/'. $owner .'/' . $repo . '/tags');

    $repositories = json_decode($response->getBody(), true);

    if ($version == 'latest') {
      return $repositories[0];
    } else {
      foreach ($repositories as $tag) {
        if ($tag['name'] == $version) {
          return $tag;
        }
      }
    }

    return null;

  }

}