<?php

namespace Kirby\Cli\Command;

use Toolkit;
use Panel;
use RuntimeException;
use Kirby;
use Kirby\Cli\Command;
use GuzzleHttp\Client;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Version extends Command {

  protected $client;

  function __construct() {
    parent::__construct();

    $this->client = new Client([
      'base_uri' => 'https://api.github.com'
    ]);
  }

  protected function configure() {
    $this->setName('version')
         ->setDescription('Prints the current versions of the core, the toolkit and the panel of your installation');
  }

  protected function getLatestRelease($repo) {
    $response = $this->client->request('GET', 'repos/getkirby/' . $repo . '/tags');
    return json_decode($response->getBody(), true)[0]['name'];
  }

  protected function execute(InputInterface $input, OutputInterface $output) {

    if(!$this->isInstalled()) {
      throw new RuntimeException('Invalid Kirby installation');
    }

    // bootstrap the core
    $this->bootstrap();

    $output->writeln("<info>Core:\t\t" . kirby::version() . "\t(latest: " . $this->getLatestRelease('kirby') . ")</info>");
    $output->writeln("<info>Toolkit:\t" . toolkit::version() . "\t(latest: " . $this->getLatestRelease('toolkit') . ")</info>");

    // also check for the panel version, if it is installed
    if(is_dir($this->dir() . '/panel')) {

      if(!is_file($this->dir() . '/panel/app/bootstrap.php')) {
        throw new RuntimeException('The panel does not seem to be correctly installed');
      }

      // bootstrap the panel
      require $this->dir() . '/panel/app/bootstrap.php';

      $output->writeln("<info>Panel:\t\t" . panel::version() . "\t(latest: " . $this->getLatestRelease('panel') . ")</info>");

    }

  }

}