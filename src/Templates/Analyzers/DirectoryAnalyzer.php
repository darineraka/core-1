<?php namespace NewUp\Templates\Analyzers;

use Illuminate\Filesystem\Filesystem;
use NewUp\Exceptions\InvalidPathException;
use NewUp\Templates\Generators\PathNormalizer;
use NewUp\Contracts\IO\DirectoryAnalyzer as DirectoryAnalyzerContract;

class DirectoryAnalyzer implements DirectoryAnalyzerContract
{
    use PathNormalizer;

    protected $fileSystem;

    public function __construct(Filesystem $fileSystem)
    {
        $this->fileSystem = $fileSystem;
    }


    /**
     * The directory to analyze.
     *
     * This function returns an array representation of the directory structure, including any
     * files and nested directories that might exist.
     *
     * @param  $directory
     * @return array
     * @throws InvalidPathException
     */
    public function analyze($directory)
    {
        if (!$this->fileSystem->exists($directory) || !$this->fileSystem->isDirectory($directory)) {
            throw new InvalidPathException($directory . ' is not a valid directory.');
        }

        $structure = $this->fileSystem->allFiles($directory);

        $newStructure = [];

        foreach ($structure as $path) {
            $type           = ($this->fileSystem->isFile($path)) ? 'file' : 'dir';
            $newStructure[] = ['path' => $this->normalizePath($path->getPathname()), 'type' => $type];
        }

        return $newStructure;
    }


}