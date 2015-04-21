<?php namespace NewUp\Contracts\IO;

interface FileTreeGenerator
{

    /**
     * Adds paths to the file tree generator.
     *
     * @param $paths
     */
    public function addPaths($paths);

    /**
     * Gets the paths that will be created.
     *
     * @return array
     */
    public function getPaths();

    /**
     * Creates the file tree in the given directory.
     *
     * @param $destinationDirectory
     * @return void
     */
    public function generate($destinationDirectory);

}