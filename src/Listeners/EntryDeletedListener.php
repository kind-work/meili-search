<?php

namespace KindWork\MeiliSearch\Listeners;

use Config;
use MeiliSearch\Client;
use Statamic\Events\EntryDeleted;

class EntryDeletedListener {
  public function handle(EntryDeleted $event) {
    $client = new Client(Config::get('meili-search.url'), Config::get('meili-search.private_key'));
    $indexes = Config::get('meili_search.indexes');

    dump($event->entry);

    //Loop over all the configured indexes
    foreach($indexes as $indexName => $indexConfigs) {
      // Loop over all the configured collection(s) for this index
      foreach($indexConfigs as $config => $fields) {
        // Get the collection handle from the config
        $collectionHandle = explode(':', $config)[1];

        // If this collection is in the index go ahead and remove the document
        if($event->entry->collectionHandle() == $collectionHandle) {
          // Get the index (or make it if it does not exist)
          $index = $client->getOrCreateIndex($indexName, ['primaryKey' => 'id']);

          // Remove the document from the index (it is being deleted ...)
          $index->deleteDocument($event->entry->id());
        }
      }
    }
  }
}
