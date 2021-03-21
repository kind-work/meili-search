# Use MeiliSearch to provide instant search

[![Statamic 3.0+](https://img.shields.io/badge/Statamic-3.0%2B-FF269E)](https://statamic.com)
[![Commercial License](https://img.shields.io/badge/License-Commercial-yellow)](#)

This Statamic v3 addon provides an easy way to integrate with [MeiliSearch](https://www.meilisearch.com/), a powerful, fast, open-source, easy to use and deploy search engine.

## Requirements
* MeiliSearch 0.14+
* PHP 7.2+
* Statamic v3+
* Laravel 7+

## Installation
You can install this addon via composer with the following command or from the Statamic control panel.

```bash
composer require kind-work/meili-search
```

For instructions on how to install MeiliSearch please see [their documentation](https://docs.meilisearch.com/guides/advanced_guides/installation.html).

## Configuration
### .env
Configure the addon by setting your MeiliSearch URL and API Keys in your `.env` file.

```yaml
MEILI_URL=http://localhost:7700
MEILI_MASTER_KEY=your-master-key-here
MEILI_PRIVATE_KEY=your-private-key-here
MEILI_PUBLIC_KEY=your-public-key-here
```

After you add your master key you can use the following command to get your private and public keys.

```bash
php please meili-search:keys
```

### Settings
To configure what collections you would like to index, publish the config file to `config/meili-search.php` by running the following command. Then customize the indexes section of the file.

```bash
php artisan vendor:publish --tag="meili-search-config"
```

## Indexing
When a collection entry is created, published, unpublished, saved or deleted via the Statamic control panel it will automatically be added, updated in or removed from the indexes configured for the appropriate collection.

The following connivence commands are available to help indexing, especially when updating content files manually.

```bash
php please meili-search:keys
php please meili-search:index help
php please meili-search:index create [Your MeiliSearch uid]
php please meili-search:index list
php please meili-search:index clear [Your MeiliSearch uid]
php please meili-search:index delete [Your MeiliSearch uid]
php please meili-search:documents help
php please meili-search:documents update
```

## Searching
Searching is best done with JavaScript talking to MeiliSearch directly. This will give you the most performant real time searches. Here is a simple example of how you could do this with [AlpineJS](https://github.com/alpinejs/alpine) and [TailwindCCS](https://tailwindcss.com).

***Note:** These steps assume you already have AlpineJS and Tailwind CCS already set up and working in your project.*

### Install the MeiliSearch NPM module
```bash
npm install meilisearch
```
or
```bash
yarn add meilisearch
```

### Import and set up MeiliSearch
```js
import { MeiliSearch } from 'meilisearch'

window.client = new MeiliSearch({
  host: 'http(s)://Your MeiliSearch address & port',
  apiKey: 'Your MeiliSearch PUBLIC Key',
});
```

### Customize your Search Component
Here is a basic autocomplete using AlpineJS and Tailwind CCS, feel free copy it, customize it, or just use it as inspiration to do something completely different.
```html
<div
  x-data="{
    searchString: '',
    results: {
      hits: [],
    },
    index: window.client.index('Your Index UID Goes Here'),
    async search() {
      this.state = 'searching';
      this.results = await this.index.search(this.searchString);
    }
  }"
  class="relative"
>
  <label
    class="sr-only"
  >
    Search
  </label>
  <input
    x-model="searchString"
    x-on:input="search()"
    type="search"
    class="
      px-2 py-1
      border-2 border-solid border-gray-300 rounded
      focus:outline-none focus:shadow-outline
    "
  />
  <div
    x-cloak
    x-show="searchString.length > 0"
    class="
      absolute left-0
      w-full
      mt-1 py-1
      bg-white
      border-2 border-solid border-grey-200 rounded
    "
  >
    <p
      x-show='results.hits.length < 1'
    >
      No results
    </p>
    <ul
      x-show='results.hits.length > 0'
    >
      <template
        x-for='result in results.hits'
        :key="result.id"
      >
        <li>
          <a
            :href="result.uri"
            class="
              px-2 py-1
              hover:bg-gray-300 focus:bg-gray-300
              transition-colors duration-100 ease-in-out delay-75
              focus:outline-none focus:shadow-outline
            "
          >
            <h2
              x-text="result.title"
            ></h2>
          </a>
        </li>
      </template>
    </ul>
  </div>
</div>
```

## Changelog
Please see the [Release Notes](https://statamic.com/addons/jrc9designstudio/meili-search/release-notes) for more information what has changed recently.

## Security
If you discover any security-related issues, please email [security@kind.work](mailto:security@kind.work) instead of using the issue tracker.

## License
This is commercial software. You may use the package for your sites. Each site requires its own license. You can purchase a licence from [The Statamic Marketplace](https://statamic.com/addons/jrc9designstudio/meili-search).
