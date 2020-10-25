<?php

namespace KindWork\MeiliSearch\Listeners;

use Config;
use MeiliSearch\Client;
use Statamic\Events\EntrySaved;

class EntrySavedListener {
  public function handle(EntrySaved $event) {
    $client = new Client(Config::get('meili_search.url'), Config::get('meili_search.private_key'));
    $indexes = Config::get('meili_search.indexes');

    //Loop over all the configured indexes
    foreach($indexes as $indexName => $indexConfigs) {
      // Loop over all the configured collection(s) for this index
      foreach($indexConfigs as $config => $fields) {
        // Get the collection handle from the config
        $collectionHandle = explode(':', $config)[1];

        // If this collection is in the index go ahead and add or remove document
        if($event->entry->collectionHandle() == $collectionHandle) {
          // Get the index (or make it if it does not exist)
          $index = $client->getOrCreateIndex($indexName, ['primaryKey' => 'id']);

          // If published add or update
          if($event->entry->published()) {
            $index->addDocuments([
              // Merge in the id and fields
              array_merge(
                // Add in the entry ID
                [ 'id' => $event->entry->id() ],

                // Filter to use only the keys defined in the config
                array_filter($event->entry->data()->toArray(), function($key) use($fields) {
                    return in_array($key, $fields);
                }, ARRAY_FILTER_USE_KEY),

                // Add in the entry URLs
                [
                  'uri' => $event->entry->uri(),
                  'api_url' => $event->entry->apiUrl(),
                ]
              )
            ]);
          } else {
            // If unpublished remove from index
            $index->deleteDocument($event->entry->id());
          }
        }
      }
    }
  }
}
