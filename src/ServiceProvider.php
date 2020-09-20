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
}
