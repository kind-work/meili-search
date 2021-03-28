<?php

return [
  /*
    |--------------------------------------------------------------------------
    | Meili Address
    |--------------------------------------------------------------------------
    |
    | This full URL for the Meili server, including protocol and port number IE:
    | - http://localhost:7700
    | - https://meili.example.com
    | - https://example.com/meili
    |
    */

  'url' => env('MEILI_URL', 'http://localhost:7700'),

  /*
  |--------------------------------------------------------------------------
  | Meili Search Master Key
  |--------------------------------------------------------------------------
  |
  | This master key for your Meili Search instance. Has access to everything!
  |
  */

  'master_key' => env('MEILI_MASTER_KEY', false),

  /*
  |--------------------------------------------------------------------------
  | Meili Search Private Key
  |--------------------------------------------------------------------------
  |
  | This private key for your Meili Search instance.
  | Has access to everything except can not list keys.
  |
  */

  'private_key' => env('MEILI_PRIVATE_KEY', false),

  /*
  |--------------------------------------------------------------------------
  | Meili Search Public Key
  |--------------------------------------------------------------------------
  |
  | This public key for your Meili Search instance.
  | Can search and retrieve documents and get the health status of Meili.
  |
  */

  'public_key' => env('MEILI_PUBLIC_KEY', false),

  /*
  |--------------------------------------------------------------------------
  | Meili Indexes
  |--------------------------------------------------------------------------
  |
  | Define the indexes you want to use, the collections you want to index
  | and the fields you want to index on those collections.
  |
  */

  // This is an example, please change it to fit your collections and fields.
  // 'indexes' => [
  //   'default' => [
  //     'collection:pages' => [ 'title', 'slug', 'content' ],
  //     'collection:blog' => [
  //       'title',
  //       'date',
  //       'content',
  //       'reading_time' => '{{ content | read_time }}'
  //     ],
  //   ],
  //   'store' => [
  //     'collection:products' => [ 'title', 'slug', 'description', 'price' ],
  //   ],
  // ],
];
