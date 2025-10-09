<?php

namespace Differ\Phpunit\Tests;

use PHPUnit\Framework\TestCase;
use Differ\Differ;

class DifferTest extends TestCase
{
    public function getFixtureFullPath(string $fixtureName): string
    {
        $parts = [__DIR__, 'fixtures', $fixtureName];
        return realpath(implode('/', $parts));
    }

    public function testGenDiff(): void
    {

        $pathToExpectedFlat = $this->getFixtureFullPath('expected_flat');

        $firstFilePathJson = $this->getFixtureFullPath('file1.json');
        $secondFilePathJson = $this->getFixtureFullPath('file2.json');
        $diff = Differ\genDiff($firstFilePathJson, $secondFilePathJson);
        $this->assertStringEqualsFile($pathToExpectedFlat, $diff);

        $firstFilePathYaml = $this->getFixtureFullPath('file1.yaml');
        $secondFilePathYaml = $this->getFixtureFullPath('file2.yaml');
        $diff = Differ\genDiff($firstFilePathYaml, $secondFilePathYaml);
        $this->assertStringEqualsFile($pathToExpectedFlat, $diff);

    }
}
