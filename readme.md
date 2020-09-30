## Swagger Merger

## Installation

`composer require artarts36/swagger-merger`

## Usage:

```php
use ArtARTs36\SwaggerMerger\Info;
use ArtARTs36\SwaggerMerger\Merger;

Merger::byJsonFile('api-docs.json')
    ->addJsonFile('package-api-docs.json')
    ->addYamlFile('package-api-docs.yml')
    ->addYamlFileWithPrefixes('package2-api-docs.yml', function (Info $info) {
        $info->setDescriptionPrefix('Package 2 :: ');
        $info->setTagsPrefix('Package 2 :: ');
    })
    ->saveAsJson();
```
