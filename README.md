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
To configure what collections you would like to index, publish the config file too `config/meili_search.php` by running the following command. Then customize the indexes section of the file.

```bash
php artisan vendor:publish --tag="meili_search-config"
```

## Changelog

Please see the [Release Notes](https://statamic.com/addons/jrc9designstudio/meili-search/release-notes) for more information what has changed recently.

## Security

If you discover any security related issues, please email [security@kind.work](mailto:security@kind.work) instead of using the issue tracker.

## License

This is commercial software. You may use the package for your sites. Each site requires its own license. You can purchase a licence from [The Statamic Marketplace](https://statamic.com/addons/jrc9designstudio/meili-search).
