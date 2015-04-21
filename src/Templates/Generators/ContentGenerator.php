<?php namespace NewUp\Templates\Generators;

use NewUp\Contracts\IO\FileTreeGenerator;
use NewUp\Contracts\Templates\Renderer;
use NewUp\Templates\Renderers\Collectors\FileNameCollector;

/**
 * Class ContentGenerator
 *
 * The content generator acts as a facade to the underlying tree generator
 * and the template renderer. It is responsible for instructing the tree generator
 * to actually generate the tree, and then uses the template renderer to get content
 * and then write them to files.
 *
 * @package NewUp\Templates\Generators
 */
class ContentGenerator
{

    use PathTreeArrayFormat;

    /**
     * The FileTreeGenerator instance.
     *
     * @var FileTreeGenerator
     */
    protected $treeGenerator;

    /**
     * The TemplateRenderer instance.
     *
     * @var Renderer
     */
    protected $templateRenderer;

    /**
     * Just a file name collector instance.
     *
     * @var FileNameCollector
     */
    protected $filePathCollector;

    public function __construct(FileTreeGenerator $treeGenerator, Renderer $renderer)
    {
        $this->treeGenerator     = $treeGenerator;
        $this->templateRenderer  = $renderer;
        $this->filePathCollector = new FileNameCollector;
    }

    /**
     * Adds paths to the underlying collectors and tree generators.
     *
     * This function will also format the paths correctly when adding them
     * to the tee generator.
     *
     * @param array $paths
     */
    public function addPaths(array $paths)
    {
        // Store the paths where they need to be.
        $this->filePathCollector->addFileNames($paths);
        $this->treeGenerator->addPaths($paths);
    }

}