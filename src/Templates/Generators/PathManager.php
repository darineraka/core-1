<?php namespace NewUp\Templates\Generators;

use NewUp\Contracts\IO\FileTreeGenerator;
use NewUp\Contracts\Templates\PathNameParser;
use NewUp\Contracts\Templates\Renderer;
use NewUp\Templates\Analyzers\DirectoryAnalyzer;
use NewUp\Templates\Parsers\YAMLParser;
use NewUp\Templates\Renderers\Collectors\FileNameCollector;

class PathManager
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

    /**
     * The DirectoryAnalyzer instance.
     *
     * @var DirectoryAnalyzer
     */
    protected $analyzer;

    /**
     * The PathNameParser instance.
     *
     * @var PathNameParser
     */
    protected $parser;

    /**
     * The YAML Parser.
     *
     * @var YAMLParser
     */
    protected $yaml;

    /**
     * Holds current PathCollector data.
     *
     * @var array
     */
    protected $currentCollectorData = [];

    /**
     * An array of paths.
     *
     * @var array
     */
    protected $paths = [];

    /**
     * Returns a new PathManager instance.
     *
     * @param  FileTreeGenerator $treeGenerator
     * @param  Renderer          $renderer
     * @param  DirectoryAnalyzer $analyzer
     * @param  PathNameParser    $parser
     */
    public function __construct(
        FileTreeGenerator $treeGenerator,
        Renderer $renderer,
        DirectoryAnalyzer $analyzer,
        PathNameParser $parser
    ) {
        $this->treeGenerator    = $treeGenerator;
        $this->templateRenderer = $renderer;
        $this->analyzer         = $analyzer;
        $this->parser           = $parser;

        $this->filePathCollector = new FileNameCollector;
        $this->yaml              = new YAMLParser;
    }

    /**
     * Adds a path to the path data collector.
     *
     * @param $path
     */
    public function addPathToCollector($path)
    {
        $this->filePathCollector->addFileNames($this->yaml->parseFile($path));
    }

    /**
     * Adds paths to the underlying collectors and tree generators.
     *
     * @param array $paths
     * @throws \NewUp\Exceptions\InvalidPathException
     */
    public function addPaths(array $paths)
    {
        $this->currentCollectorData = $this->filePathCollector->collect();

        foreach ($paths as $path) {
            $this->addPath($path);
        }
    }

    /**
     * Adds a single path to the underlying collectors and tree generators.
     *
     * This method will add all processed paths to the internal collection.
     * Additionally, it will return the array of paths that were processed
     * during the current invocation.
     *
     * @param  $path
     * @throws \NewUp\Exceptions\InvalidPathException
     * @return array
     */
    private function addPath($path)
    {
        $paths = $this->analyzer->analyze($path);

        $newPaths = [];

        foreach ($paths as $filePath) {
            $newPathInformation             = $filePath;
            $newPathInformation['original'] = $filePath['path'];

            if ($this->getCollectorValue($filePath['path']) !== null) {
                // If the value from the collector is not null, we need to process the path value
                // using the template renderer.
                $newPathInformation['path'] = $this->templateRenderer
                    ->renderString($this->getCollectorValue($filePath['path']));
            } else {
                // If the value IS null, we need to process the path value using the path parser.
                $newPathInformation['path'] = $this->parser->processPath($filePath['path']);
            }

            $newPaths[]    = $newPathInformation;
            $this->paths[] = $newPathInformation;

            // Add the new file path association to the collector.
            $this->addRawToCollector($filePath['path'], $newPathInformation['path']);
        }

        return $newPaths;
    }

    /**
     * Returns the value stored in the path collector for the given path.
     *
     * @param $key
     * @return null|mixed
     */
    private function getCollectorValue($key)
    {
        if (isset($this->currentCollectorData['sys_pathNames'])) {
            if (array_key_exists($key, $this->currentCollectorData['sys_pathNames'])) {
                return $this->currentCollectorData['sys_pathNames'][$key];
            }
        }

        return null;
    }

    /**
     * Adds a raw set of collector replacement values to the path data collector.
     *
     * @param $key
     * @param $value
     */
    public function addRawToCollector($key, $value)
    {
        $this->filePathCollector->addFileNames([$key => $value]);
    }

    public function emitStructure($destination)
    {
        $this->treeGenerator->addPaths($this->getPaths());

        return $this->treeGenerator->generate($destination);
    }

    /**
     * Returns all the processed paths.
     *
     * @return array
     */
    public function getPaths()
    {
        return $this->paths;
    }

    /**
     * Returns the file name collector instance.
     *
     * @return FileNameCollector
     */
    public function getCollector()
    {
        return $this->filePathCollector;
    }

}