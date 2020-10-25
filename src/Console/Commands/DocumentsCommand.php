<?php

namespace KindWork\MeiliSearch\Console\Commands;

use Config;
use MeiliSearch\Client;
use \Statamic\Facades\Entry;
use Illuminate\Support\Arr;
use Illuminate\Console\Command;
use Statamic\Console\RunsInPlease;

class DocumentsCommand extends Command {
  use RunsInPlease;

  protected $name = 'meili-search:documents';
  protected $description = 'Document convenience commands';
  protected $signature = 'meili-search:documents {method=null}';
  protected $methods = ['help', 'update'];
  protected $client;

  public function handle() {
    $this->client = new Client(Config::get('meili_search.url'), Config::get('meili_search.private_key'));
    $method = $this->argument('method');

    if($method == 'null') {
      $this->help();
      return;
    } elseif (!in_array($method, $this->methods, true)) {
      $this->warn('Method not supported. Supported methods are: ' . join(', ', $this->methods));
      return;
    }

    $this->{$method}();
  }

  private function help() {
    $this->info('Usage:');
    $this->line('
      meili-search:documents [method=help]
    ');
    $this->info('Methods:');
    $this->line('
        - help          Show the help
        - update        Update or remove documents from the indexes
    ');
  }

  private function update() {
    // Get the indexes configured
    $indexes = Config::get('meili_search.indexes');

    // For each index lets get the documents and update
    foreach($indexes as $indexName => $indexConfigs) {
      // Say we have stared indexing a specific index
      $this->info('Updating index: ' . $indexName);

      // For each index map over the configured collections
      // Then collapse them into a 1 dimensional array
      $documents = Arr::collapse(array_map(function($config, $fields) {
        // Get the collection handle (Maybe get things other than collections later?)
        $collectionHandle = explode(':', $config)[1];

        // Query for the entries in the collection
        $entries = Entry::query()
          ->where('collection', $collectionHandle)
          ->where('published', true)
          ->get()
          ->preProcessForIndex()
          ->toArray();

        // Return the document data for indexing with a map
        return array_map(function($entry) use($fields) {

          // Merge in the id and fields
          return array_merge(
            // Add in the entry ID
            [ 'id' => $entry ->id() ],

            // Filter to use only the keys defined in the config
            array_filter($entry->data()->toArray(), function($key) use($fields) {
                return in_array($key, $fields);
            }, ARRAY_FILTER_USE_KEY),

            // Add in the entry URLs
            [
              'uri' => $entry->uri(),
              'api_url' => $entry->apiUrl(),
            ]
          );
        }, $entries);
      }, array_keys($indexConfigs), $indexConfigs));

      // Get the index (or make it if it does not exist)
      $index = $this->client->getOrCreateIndex($indexName, ['primaryKey' => 'id']);

      // Find the removed document ids
      $removedDocuments = array_merge(
        array_diff(
          array_map(function($entry) { return $entry['id']; }, $index->getDocuments()),
          array_map(function($entry) { return $entry['id']; }, $documents)
        )
      );

      // Delete all the removed documents
      $index->deleteDocuments($removedDocuments);

      // Add or update documents
      $index->addDocuments($documents);

      // Report back that we are done
      $this->info('Finished updating index: ' . $indexName);
    }
  }
}
