<?php

namespace Differ\Phpunit\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

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
        $diff = genDiff($firstFilePathJson, $secondFilePathJson);
        $this->assertStringEqualsFile($pathToExpectedStylish, $diff);

        $diff = genDiff($firstFilePathYaml, $secondFilePathYaml);
        $this->assertStringEqualsFile($pathToExpectedStylish, $diff);

        $diff = genDiff($firstFilePathJson, $secondFilePathJson, "stylish");
        $this->assertStringEqualsFile($pathToExpectedStylish, $diff);

        $diff = genDiff($firstFilePathYaml, $secondFilePathYaml, "stylish");
        $this->assertStringEqualsFile($pathToExpectedStylish, $diff);

        // Plain
        $diff = genDiff($firstFilePathJson, $secondFilePathJson, "plain");
        $this->assertStringEqualsFile($pathToExpectedPlain, $diff);

        $diff = genDiff($firstFilePathYaml, $secondFilePathYaml, "plain");
        $this->assertStringEqualsFile($pathToExpectedPlain, $diff);

        //Json
        $diff = genDiff($firstFilePathJson, $secondFilePathJson, "json");
        $this->assertStringEqualsFile($pathToExpectedJson, $diff);

        $diff = genDiff($firstFilePathYaml, $secondFilePathYaml, "json");
        $this->assertStringEqualsFile($pathToExpectedJson, $diff);
    }
}
