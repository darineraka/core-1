<?php namespace NewUp\Templates\Generators;

use NewUp\Contracts\Templates\Renderer;
use NewUp\Filesystem\Filesystem;

/**
 * Class ContentGenerator
 *
 * The ContentGenerator is a facade that helps manage the interactions of
 * the path generators and the actual template generation. Using it is for
 * the most part exactly the same as using the `PathManager`.
 *
 * - To generate only the directory structure, call the 'emitStructure()` method
 * - To generate both the directory structure and file contents, call the `generateContent()` method
 *
 * @package NewUp\Templates\Generators
 */
class ContentGenerator
{

    /**
     * The PathManager instance.
     *
     * @var PathManager
     */
    protected $pathManager = null;

    /**
     * The Filesystem implementation instance.
     *
     * @var Filesystem
     */
    protected $fileSystem = null;

    public function __construct(PathManager $pathManager, Filesystem $fileSystem)
    {
        $this->pathManager = $pathManager;
        $this->fileSystem  = $fileSystem;
    }

    /**
     * Returns the PathManager instance.
     *
     * @return PathManager
     */
    public function getPathManager()
    {
        return $this->pathManager;
    }

    /**
     * Returns the Renderer implementation instance.
     *
     * @return Renderer
     */
    public function getRenderer()
    {
        return $this->templateRenderer;
    }

    /**
     * Generates files and contents in the provided destination directory.
     *
     * @param  $destination
     * @return array
     */
    public function generateContent($destination)
    {
        $pathsWrittenTo = [];

        $packageStructure = $this->pathManager->emitStructure($destination);
        $this->pathManager->getRenderer()->setIgnoreUnloadedTemplateErrors(true);

        foreach ($packageStructure as $packageFile) {
            if ($this->fileSystem->exists($packageFile['full'])) {
                $packageFileContents = $this->pathManager->getRenderer()->render($packageFile['original']);

                if ($packageFileContents != null && strlen($packageFileContents) > 0) {
                    $this->fileSystem->put($packageFile['full'], $packageFileContents);
                    $pathsWrittenTo[] = $packageFile;
                }

            }
        }

        $this->pathManager->getRenderer()->setIgnoreUnloadedTemplateErrors(false);

        return $pathsWrittenTo;
    }

    /**
     * Handle dynamic calls to the underlying PathManager instance.
     *
     * @param $method
     * @param $args
     * @return mixed
     */
    public function __call($method, $args)
    {
        switch (count($args)) {
            case 0:
                return $this->pathManager->$method();
            case 1:
                return $this->pathManager->$method($args[0]);
            case 2:
                return $this->pathManager->$method($args[0], $args[1]);
            case 3:
                return $this->pathManager->$method($args[0], $args[1], $args[2]);
            case 4:
                return $this->pathManager->$method($args[0], $args[1], $args[2], $args[3]);
            default:
                return call_user_func(array($this->pathManager, $method), $args);
        }
    }

}