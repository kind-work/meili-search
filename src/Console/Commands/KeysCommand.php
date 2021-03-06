<?php

namespace KindWork\MeiliSearch\Console\Commands;

use Config;
use MeiliSearch\Client;
use Illuminate\Console\Command;
use Statamic\Console\RunsInPlease;

class KeysCommand extends Command {
  use RunsInPlease;

  protected $name = 'meili-search:keys';
  protected $description = 'Get the master key derived private and public keys';
  protected $signature = 'meili-search:keys';
  protected $client;

  public function handle() {
    $this->client = new Client(Config::get("meili-search.url"), Config::get("meili-search.master_key"));
    $keys = $this->client->getKeys();
    $this->info('Keys:');
    $this->line('
      MEILI_MASTER_KEY=' . Config::get("meili-search.master_key") . '
      MEILI_PRIVATE_KEY=' . $keys['private'] . '
      MEILI_PUBLIC_KEY=' . $keys['public'] . '
    ');
  }
}
