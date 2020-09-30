<?php

namespace ArtARTs36\SwaggerMerger;

use Symfony\Component\Yaml\Yaml;

class Merger
{
    protected $rootPath;

    protected $content;

    public function __construct(string $rootPath, array $rootContent)
    {
        $this->rootPath = $rootPath;
        $this->content = Arr::allocateIfKeyNotExists($rootContent, 'paths', 'components');
    }

    public static function byYamlFile(string $rootPath): Merger
    {
        return static::byYaml(file_get_contents($rootPath), $rootPath);
    }

    public static function byYaml(string $input, string $rootPath): Merger
    {
        return new static($rootPath, Yaml::parse($rootPath));
    }

    public static function byJsonFile(string $rootPath): Merger
    {
        return static::byJson(file_get_contents($rootPath), $rootPath);
    }

    public static function byJson(string $json, string $rootPath)
    {
        return new static($rootPath, json_decode($json, true));
    }

    public function addYamlFile(string $path, string $namePrefix = ''): self
    {
        return $this->addYaml(file_get_contents($path), $namePrefix);
    }

    public function addYaml(string $input, string $namePrefix = ''): self
    {
        return $this->merge(Yaml::parse($input), $namePrefix);
    }

    public function addContent(array $content, string $namePrefix = ''): self
    {
        return $this->merge($content, $namePrefix);
    }

    public function addJsonFile(string $path, string $namePrefix = ''): self
    {
        return $this->addJson(file_get_contents($path), $namePrefix);
    }

    public function addJson(string $input, string $namePrefix = ''): self
    {
        return $this->merge(json_decode($input, true), $namePrefix);
    }

    public function saveAsJson(string $path = null): bool
    {
        return (bool) file_put_contents($path ?? $this->rootPath, json_encode($this->content));
    }

    protected function merge(array $content, string $namePrefix): self
    {
        unset($content['openapi'], $content['info']);

        if (! empty($content['paths'])) {
            foreach ($content['paths'] as $path => &$pathDescription) {
                foreach ($pathDescription as &$method) {
                    $method['summary'] = $namePrefix . $method['summary'];
                    $method['description'] = $namePrefix . $method['description'];
                }

                unset($this->content['paths'][$path]);
            }
        }

        $this->content = array_merge_recursive($this->content, $content);

        return $this;
    }
}
