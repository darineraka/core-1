<?php namespace NewUp\Tests\Generators;

use Illuminate\Filesystem\Filesystem;
use NewUp\Templates\Generators\FileSystemTreeGenerator;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;

class FileSystemTreeGeneratorIOTest extends \PHPUnit_Framework_TestCase
{

    /**
     * The vfsStreamDirectory instance.
     *
     * @var vfsStreamDirectory
     */
    private $vfs;

    protected function setUp()
    {
        $this->vfs = vfsStream::setup('fst');
    }

    private function getGenerator()
    {
        $fileSystem = new Filesystem;
        $generator  = new FileSystemTreeGenerator($fileSystem);

        $generator->addPaths([
            'someKey'    => ['path' => 'some/file.txt', 'type' => 'file'],
            'anotherKey' => ['path' => 'some/nested/file.txt', 'type' => 'file'],
            'thirdKey'   => ['path' => 'some/dir', 'type' => 'dir'],
            'fourthKey'  => ['path' => 'root.txt', 'type' => 'file'],
            'ignore'     => ['path' => '.gitignore', 'type' => 'file'],
        ]);

        return $generator;
    }

    public function testFileSystemTreeGeneratorCanCreateFilesAndDirectories()
    {
        $g = $this->getGenerator();

        $g->generate(vfsStream::url('fst'));

        // These are the directories and files that should be created.
        $testChildren = [
            'some',
            'some/file.txt',
            'some/nested',
            'some/dir',
            'some/nested/file.txt',
            '.gitignore',
            'root.txt'
        ];

        foreach ($testChildren as $child) {
            $this->assertTrue($this->vfs->hasChild($child));
        }

    }

    public function testFileSystemTreeGeneratorReturnsAnArrayOfTheFilesCreated()
    {
        $g     = $this->getGenerator();
        $paths = $g->generate(vfsStream::url('fst'));

        $this->assertCount(5, $paths);

        foreach (['ignore', 'fourthKey', 'thirdKey', 'anotherKey', 'someKey'] as $key) {
            $this->assertArrayHasKey($key, $paths);
        }

        foreach ($paths as $path) {
            foreach (['path', 'type', 'full'] as $pathPart) {
                $this->assertArrayHasKey($pathPart, $path);
            }
        }

    }

}