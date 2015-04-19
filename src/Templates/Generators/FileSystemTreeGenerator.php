<?php namespace NewUp\Templates\Generators;

use Illuminate\Filesystem\Filesystem;
use NewUp\Contracts\IO\FileTreeGenerator;

class FileSystemTreeGenerator implements FileTreeGenerator {

    /**
     * A collection of paths and directories to create.
     *
     * @var array
     */
    protected $paths = [];

    /**
     * The file system instance.
     *
     * @var Filesystem
     */
    protected $fileSystem;

    public function __construct(Filesystem $fileSystem)
    {
        $this->fileSystem = $fileSystem;
    }

    /**
     * Adds paths to the file tree generator.
     *
     * The paths added using this method should already be processed, meaning that
     * the final path is already free of template processors, placeholders, etc.
     *
     * @param $paths
     * @return mixed
     */
    public function addPaths($paths)
    {
        $this->paths = $this->paths + $paths;
    }

    /**
     * Normalizes the use of '/' and '\' in a path.
     *
     * @param  $path
     * @return string
     */
    private function normalizePath($path)
    {
        $newPath = str_replace('/', DIRECTORY_SEPARATOR, $path);
        $newPath = str_replace('\\', DIRECTORY_SEPARATOR, $newPath);

        return $newPath;
    }

    /**
     * Converts all instances of '\' or '/' to the system directory separator.
     */
    private function normalizePaths()
    {
        foreach ($this->paths as $key => $path)
        {
            $this->paths[$key] = $this->normalizePath($path);
        }
    }

    /**
     * Sorts the paths so that the most specific paths are listed first.
     *
     * @return array
     */
    private function sortPaths()
    {
        $this->normalizePaths();

        uasort($this->paths, function($a, $b)
        {
            if ($a == $b)
            {
                return 0;
            }

            return (substr_count($a['path'], DIRECTORY_SEPARATOR) > substr_count($b['path'], DIRECTORY_SEPARATOR)) ? -1 : 1;
        });

        return $this->paths;
    }

    /**
     * Gets the paths that will be created.
     *
     * @return mixed
     */
    public function getPaths()
    {
        return $this->sortPaths();
    }

    /**
     * Creates the file tree in the given directory.
     *
     * @param  $destinationDirectory
     * @return array
     */
    public function generate($destinationDirectory)
    {
        foreach ($this->getPaths() as $path)
        {
            $fullPath = $destinationDirectory.DIRECTORY_SEPARATOR.$path['path'];

            if ($path['type'] == 'dir')
            {
                $this->fileSystem->makeDirectory($fullPath, 0755, true, true);
            }
            else
            {
                // There are two steps here:
                // 1st: Recursively create the directory structure for the file (it might not exist)
                // 2nd: Create an empty file using `touch()` since we are guaranteed the directory structure exists.
                $this->fileSystem->makeDirectory(dirname($fullPath), 0755, true, true);
                touch($fullPath);
            }
        }
    }


}