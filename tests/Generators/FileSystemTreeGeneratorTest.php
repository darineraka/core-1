<?php namespace NewUp\Tests\Generators;

use NewUp\Templates\Generators\FileSystemTreeGenerator;

class FileSystemTreeGeneratorTest extends \PHPUnit_Framework_TestCase
{

    public function testAddPathsWorks()
    {
        $g = $this->getGenerator();
        $g->addPaths(['key' => ['path' => '', 'type' => '']]);
        $this->assertCount(1, $g->getPaths());
    }

    private function getGenerator()
    {
        $fileSystem = $this->getMock('Illuminate\Filesystem\Filesystem');
        $generator  = new FileSystemTreeGenerator($fileSystem);

        return $generator;
    }

    public function testGeneratorIsSortingThePathsInTheCorrectOrder()
    {
        $g = $this->getGenerator();

        $startPaths = [
            'first.php' => ['path' => 'this/should/appear/last/file.php', 'type' => 'file'],
            'second'    => ['path' => 'this/should/appear/first/because/its/nested/further/file.php', 'type' => 'file']
        ];

        $g->addPaths($startPaths);

        // Just test the keys, as the directory separators are normalized.
        $this->assertEquals(['second', 'first.php'], array_keys($g->getPaths()));
    }
}