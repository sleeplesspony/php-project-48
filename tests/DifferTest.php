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
        $firstFilePath = $this->getFixtureFullPath('file1.json');
        $secondFilePath = $this->getFixtureFullPath('file2.json');
        $diff = Differ\genDiff($firstFilePath, $secondFilePath);
        $this->assertStringEqualsFile($pathToExpectedFlat, $diff);
    }
}
