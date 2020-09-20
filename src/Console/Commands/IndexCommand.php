<?php

namespace KindWork\MeiliSearch\Console\Commands;

use Config;
use MeiliSearch\Client;
use Illuminate\Console\Command;
use Statamic\Console\RunsInPlease;

class IndexCommand extends Command {
    use RunsInPlease;

    protected $name = 'meili-search:index';
    protected $description = 'Index convenience commands';
    protected $signature = 'meili-search:index {method=null} {index=null}';
    protected $methods = ['help', 'create', 'list', 'clear', 'delete'];
    protected $index;
    protected $client;

    public function handle() {
      $this->index = $this->argument('index');
      $method = $this->argument('method');
      $this->client = new Client(Config::get("meili_search.url"), Config::get("meili_search.private_key"));

      if ($method == 'null') {
        $this->help();
      } elseif (!$this->index && $method != 'index') {
        $this->warn('A index name is required!');
      } elseif (!in_array($method, $this->methods, true)) {
        $this->warn('Method not supported. Supported methods are: ' . join(', ', $this->methods));
      } else {
        $this->{$method}();
      }
    }

    private function help() {
      $this->info('Usage:');
      $this->line('
        meili-search:index [method=help] [index=null] [primaryKey=id]
      ');
      $this->info('Methods:');
      $this->line('
          - help          Show the help
          - create        Create an search index
          - list          List all indexes
          - clear         Clear all the enteries from an index
          - delete        Delete a search index
      ');
      $this->info('Arguments:');
      $this->line('
          - index         The uid of the index to use
      ');
    }

    private function create() {
      $this->client->createIndex($this->index, ['primaryKey' => 'id']);
      $this->info('Index ' . $this->index . ' created');
    }

    private function list() {
      $indexes = $this->client->getAllIndexes();
      $this->info('Indexes:');
      foreach($indexes as $index) {
        $stats = $index->stats();
        $this->line('
          UID:              ' . $index->getUid() . '
          Primary Key:      ' . $index->getPrimaryKey() . '
          # of Documents:   ' . $stats['numberOfDocuments'] . '
          Is Indexing:      ' . ($stats['isIndexing'] ? 'true' : 'false') . '
        ');
      }
    }

    private function clear() {
      $index = $this->client->getIndex($this->index);
      $index->deleteAllDocuments();
      $this->info('Index ' . $this->index . ' cleared');
    }

    private function delete() {
      $index = $this->client->getIndex($this->index);
      $index->delete();
      $this->info('Index ' . $this->index . ' deleted');
    }
}
