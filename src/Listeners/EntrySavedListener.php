<?php

namespace KindWork\MeiliSearch\Listeners;

use Config;
use MeiliSearch\Client;
use Statamic\Facades\Entry;
use Statamic\Facades\Parse;
use Statamic\Events\EntrySaved;
use Statamic\View\Cascade as ViewCascade;

class EntrySavedListener
{
  public function handle(EntrySaved $event)
  {
    $client = new Client(
      Config::get('meili-search.url'),
      Config::get('meili-search.private_key')
    );
    $indexes = Config::get('meili-search.indexes');

    //Loop over all the configured indexes
    foreach ($indexes as $indexName => $indexConfigs) {
      // Loop over all the configured collection(s) for this index
      foreach ($indexConfigs as $config => $fields) {
        // Get the collection handle from the config
        $collectionHandle = explode(':', $config)[1];

        // If this collection is in the index go ahead and add or remove document
        if ($event->entry->collectionHandle() == $collectionHandle) {
          // Get the index (or make it if it does not exist)
          $index = $client->getOrCreateIndex($indexName, [
            'primaryKey' => 'id',
          ]);

          // If published add or update
          if ($event->entry->published()) {
            // Find and parse any antlers fields
            $antlersFields = array_merge(
              ...array_map(
                function ($field) use ($fields, $event) {
                  $viewCascade = app(ViewCascade::class)->toArray();

                  return [
                    $field => (string) Parse::template(
                      // Get the template by key on fields
                      $fields[$field],
                      // Augment the data for the template and pass in the cascade
                      array_merge(
                        $viewCascade,
                        $event->entry->toAugmentedArray()
                      )
                    ),
                  ];
                },
                array_filter(array_keys($fields), function ($key) {
                  // Antlers fields to parse have a key that is a of type string
                  return gettype($key) == 'string';
                })
              )
            );

            $index->addDocuments([
              // Merge in the id and fields
              array_merge(
                // Add in the entry ID
                ['id' => $event->entry->id()],

                // Filter to use only the keys defined in the config
                array_filter(
                  $event->entry->data()->toArray(),
                  function ($key) use ($fields) {
                    return in_array($key, $fields);
                  },
                  ARRAY_FILTER_USE_KEY
                ),

                // Add the antlers fields to the array
                $antlersFields,

                // Add in the entry URLs
                [
                  'uri' => $event->entry->uri(),
                  'api_url' => $event->entry->apiUrl(),
                ]
              ),
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
