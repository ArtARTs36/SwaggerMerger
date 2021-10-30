<?php

namespace ArtARTs36\SwaggerMerger;

use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\Yaml\Yaml;

/**
 * @method self addYamlFileWithPrefixes(string $path, callable|Info $setup)
 * @method self addYamlWithPrefixes(string $input, callable|Info $setup)
 * @method self addJsonFileWithPrefixes(string $path, callable|Info $setup)
 * @method self addJsonWithPrefixes(string $input, callable|Info $setup)
 */
class Merger
{
    protected $rootPath;

    protected $content;

    protected $info;

    public function __construct(string $rootPath, array $rootContent)
    {
        $this->rootPath = $rootPath;
        $this->content = Arr::allocateIfKeyNotExists($rootContent, 'paths', 'components');
        $this->info = new Info();
    }

    public static function byYamlFile(string $rootPath): Merger
    {
        return static::byYaml(file_get_contents($rootPath), $rootPath);
    }

    public static function byYaml(string $input, string $rootPath): Merger
    {
        return new static($rootPath, Yaml::parse($input));
    }

    public static function byJsonFile(string $rootPath): Merger
    {
        return static::byJson(file_get_contents($rootPath), $rootPath);
    }

    public static function byJson(string $json, string $rootPath)
    {
        return new static($rootPath, json_decode($json, true));
    }

    public function __call($name, $arguments)
    {
        if (strpos($name, 'WithPrefixes') === false ||
            count($arguments) !== 2 ||
            ! (is_callable($arguments[1]) || $arguments[1] instanceof Info) ||
            (! ($name = str_replace('WithPrefixes', '', $name)) || ! method_exists($this, $name))
        ) {
            throw new \BadMethodCallException();
        }

        $this->info = $arguments[1];

        if (is_callable($this->info)) {
            ($this->info)($info = new Info());

            $this->info = $info;
        }

        $result = $this->$name($arguments[0]);

        $this->info = new Info();

        return $result;
    }

    public function addYamlFile(string $path): self
    {
        return $this->addYaml(file_get_contents($path));
    }

    public function addYaml(string $input): self
    {
        return $this->merge(Yaml::parse($input));
    }

    public function addContent(array $content): self
    {
        return $this->merge($content);
    }

    public function addJsonFile(string $path): self
    {
        return $this->addJson(file_get_contents($path));
    }

    public function addJson(string $input): self
    {
        return $this->merge(json_decode($input, true));
    }

    public function saveAsJson(?string $path = null): bool
    {
        return (bool) file_put_contents($path ?? $this->rootPath, json_encode($this->content));
    }

    public function saveAsYaml(?string $path = null): bool
    {
        return (bool) file_put_contents(
            $path ?? $this->rootPath,
            Yaml::dump($this->content, Yaml::DUMP_MULTI_LINE_LITERAL_BLOCK)
        );
    }

    protected function merge(
        #[ArrayShape([
            'openapi' => 'array',
            'info' => 'array',
            'paths' => 'array',
        ])]
        array $content
    ): self {
        unset($content['openapi'], $content['info']);

        if (! empty($content['paths'])) {
            $content['paths'] = $this->preparePaths($content['paths']);
        }

        $this->content = array_merge_recursive($this->content, $content);

        return $this;
    }

    protected function preparePaths(array $paths): array
    {
        if ($this->info->isEmpty()) {
            return $paths;
        }

        foreach ($paths as &$pathDescription) {
            foreach ($pathDescription as &$method) {
                $method = Arr::prependIfKeyExists($method, 'summary', $this->info->getDescriptionPrefix());
                $method = Arr::prependIfKeyExists($method, 'description', $this->info->getDescriptionPrefix());

                if (!empty($method['tags']) && ! empty($this->info->getTagsPrefix())) {
                    $method['tags'] = Arr::prepend((array) $method['tags'], $this->info->getTagsPrefix());
                }
            }
        }

        return $paths;
    }
}
