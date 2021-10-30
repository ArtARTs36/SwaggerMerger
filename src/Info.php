<?php

namespace ArtARTs36\SwaggerMerger;

class Info
{
    protected $tagsPrefix;

    protected $descriptionPrefix;

    public function __construct(string $tagsPrefix = '', string $descriptionPrefix = '')
    {
        $this->tagsPrefix = $tagsPrefix;
        $this->descriptionPrefix = $descriptionPrefix;
    }

    public function setTagsPrefix(string $tagsPrefix): self
    {
        $this->tagsPrefix = $tagsPrefix;

        return $this;
    }

    public function setDescriptionPrefix(string $descriptionPrefix): self
    {
        $this->descriptionPrefix = $descriptionPrefix;

        return $this;
    }

    public function getTagsPrefix(): string
    {
        return $this->tagsPrefix;
    }

    public function getDescriptionPrefix(): string
    {
        return $this->descriptionPrefix;
    }

    public function isEmpty(): bool
    {
        return $this->descriptionPrefix === '' && $this->tagsPrefix === '';
    }

    public function isNotEmpty(): bool
    {
        return ! $this->isEmpty();
    }
}
