## Swagger Merger

## Installation

`composer require artarts36/swagger-merger`

## Usage:

```php
use ArtARTs36\SwaggerMerger\Merger;

Merger::byJsonFile('api-docs.json')
    ->addJsonFile('package-api-docs.json', 'Package :: ')
    ->addYamlFile('package-api-docs.yml')
    ->saveAsJson();
```
