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
    $this->client = new Client(Config::get("meili_search.url"), Config::get("meili_search.master_key"));
    $keys = $this->client->getKeys();
    $this->info('Keys:');
    $this->line('
      Master:   ' . Config::get("meili_search.master_key") . '
      Private:  ' . $keys['private'] . '
      Public:   ' . $keys['public'] . '
    ');
  }
}
