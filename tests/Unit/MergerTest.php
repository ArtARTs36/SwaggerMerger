<?php

namespace ArtARTs36\SwaggerMerger\Tests\Unit;

use ArtARTs36\SwaggerMerger\Merger;
use ArtARTs36\SwaggerMerger\Tests\TestCase;

final class MergerTest extends TestCase
{
    public function providerForTestMergeTwoYaml(): array
    {
        return [
            [
                __DIR__ . '/../resources/01_merge_two_yamls/01_set/1.yml',
                __DIR__ . '/../resources/01_merge_two_yamls/01_set/2.yml',
                __DIR__ . '/../resources/01_merge_two_yamls/01_set/result._yml',
            ],
        ];
    }

    /**
     * @dataProvider providerForTestMergeTwoYaml
     */
    public function testMergeTwoYaml(string $onePath, string $twoPath, string $resultPath): void
    {
        Merger::byYamlFile($onePath)
            ->addYamlFile($twoPath)
            ->saveAsYaml($path = __DIR__ . '/../resources/01_merge_two_yamls/last_result');

        self::assertFileEquals($resultPath, $path);
    }
}
