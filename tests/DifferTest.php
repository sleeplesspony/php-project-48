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
        $pathToExpectedStylish = $this->getFixtureFullPath('expected_stylish');
        $pathToExpectedPlain = $this->getFixtureFullPath('expected_plain');
        $pathToExpectedJson = $this->getFixtureFullPath('expected_json');

        $firstFilePathJson = $this->getFixtureFullPath('file1.json');
        $secondFilePathJson = $this->getFixtureFullPath('file2.json');

        $firstFilePathYaml = $this->getFixtureFullPath('file1.yaml');
        $secondFilePathYaml = $this->getFixtureFullPath('file2.yaml');

        // Stylish
        $diff = Differ\genDiff($firstFilePathJson, $secondFilePathJson);
        $this->assertStringEqualsFile($pathToExpectedStylish, $diff);

        $diff = Differ\genDiff($firstFilePathYaml, $secondFilePathYaml);
        $this->assertStringEqualsFile($pathToExpectedStylish, $diff);

        // Plain
        $diff = Differ\genDiff($firstFilePathJson, $secondFilePathJson, "plain");
        $this->assertStringEqualsFile($pathToExpectedPlain, $diff);

        $diff = Differ\genDiff($firstFilePathYaml, $secondFilePathYaml, "plain");
        $this->assertStringEqualsFile($pathToExpectedPlain, $diff);

        //Json
        $diff = Differ\genDiff($firstFilePathJson, $secondFilePathJson, "json");
        $this->assertStringEqualsFile($pathToExpectedJson, $diff);

        $diff = Differ\genDiff($firstFilePathYaml, $secondFilePathYaml, "json");
        $this->assertStringEqualsFile($pathToExpectedJson, $diff);
    }
}
