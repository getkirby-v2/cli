<?php

namespace Kirby\Cli;

use Kirby;
use RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MakeUserCommand extends BaseCommand {

  protected function configure() {
    $this->setName('make:user')
         ->setDescription('Creates a new user account')
         ->addOption(
           'username',
           'u',
           InputOption::VALUE_REQUIRED,
           'Sets the username for the new account'
        )
        ->addOption(
           'password',
           'p',
           InputOption::VALUE_REQUIRED,
           'Sets the password for the new account'
        )
        ->addOption(
           'email',
           'e',
           InputOption::VALUE_OPTIONAL,
           'Sets the email address for the new account'
        );
  }

  protected function execute(InputInterface $input, OutputInterface $output) {

    if(!$this->isInstalled()) {
      throw new RuntimeException('Invalid Kirby installation');
    }

    $user = $this->kirby()->site()->users()->create([
      'username' => $input->getOption('username'),
      'password' => $input->getOption('password'),
      'email'    => $input->getOption('email'),
      'role'     => 'admin',
      'language' => 'en'
    ]);

    $output->writeln('<comment>The user "' . $user->username() . '" has been created</comment>');

  }

}