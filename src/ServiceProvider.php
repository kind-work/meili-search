<?php

namespace KindWork\MeiliSearch;

use Statamic\Events\EntrySaved;
use Statamic\Events\EntryDeleted;
use Statamic\Providers\AddonServiceProvider;
use KindWork\MeiliSearch\Listeners\EntrySavedListener;
use KindWork\MeiliSearch\Listeners\EntryDeletedListener;

class ServiceProvider extends AddonServiceProvider {

  protected $commands = [
    Console\Commands\DocumentsCommand::class,
    Console\Commands\IndexCommand::class,
    Console\Commands\KeysCommand::class,
  ];

  protected $listen = [
    EntrySaved::class => [ EntrySavedListener::class ],
    EntryDeleted::class => [ EntryDeletedListener::class ],
  ];

  public function boot() {
    parent::boot();
  }

  protected function bootAddonConfig() {
    $this->publishes([
      __DIR__.'/../resources/config/meili_search.php' => config_path('meili_search.php'),
    ]);
    return $this;
  }

  public function register() {
    $this->mergeConfigFrom(
      __DIR__.'/../resources/config/meili_search.php', 'meili_search'
    );
  }
}
