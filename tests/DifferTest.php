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
        $pathToExpectedStylish = $this->getFixtureFullPath('expected_stylish');
        $pathToExpectedPlain = $this->getFixtureFullPath('expected_plain');

        // Flat
        $firstFilePathJson = $this->getFixtureFullPath('file_flat1.json');
        $secondFilePathJson = $this->getFixtureFullPath('file_flat2.json');
        $diff = Differ\genDiff($firstFilePathJson, $secondFilePathJson);
        $this->assertStringEqualsFile($pathToExpectedFlat, $diff);

        $firstFilePathYaml = $this->getFixtureFullPath('file_flat1.yaml');
        $secondFilePathYaml = $this->getFixtureFullPath('file_flat2.yaml');
        $diff = Differ\genDiff($firstFilePathYaml, $secondFilePathYaml);
        $this->assertStringEqualsFile($pathToExpectedFlat, $diff);

        // Stylish
        $firstFilePathJson = $this->getFixtureFullPath('file1.json');
        $secondFilePathJson = $this->getFixtureFullPath('file2.json');
        $diff = Differ\genDiff($firstFilePathJson, $secondFilePathJson);
        $this->assertStringEqualsFile($pathToExpectedStylish, $diff);

        $firstFilePathJson = $this->getFixtureFullPath('file1.yaml');
        $secondFilePathJson = $this->getFixtureFullPath('file2.yaml');
        $diff = Differ\genDiff($firstFilePathJson, $secondFilePathJson);
        $this->assertStringEqualsFile($pathToExpectedStylish, $diff);

        // Plain
        $firstFilePathJson = $this->getFixtureFullPath('file1.json');
        $secondFilePathJson = $this->getFixtureFullPath('file2.json');
        $diff = Differ\genDiff($firstFilePathJson, $secondFilePathJson, "plain");
        $this->assertStringEqualsFile($pathToExpectedPlain, $diff);

        $firstFilePathJson = $this->getFixtureFullPath('file1.yaml');
        $secondFilePathJson = $this->getFixtureFullPath('file2.yaml');
        $diff = Differ\genDiff($firstFilePathJson, $secondFilePathJson, "plain");
        $this->assertStringEqualsFile($pathToExpectedPlain, $diff);
    }
}
