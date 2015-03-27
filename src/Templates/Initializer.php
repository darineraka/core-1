<?php namespace NewUp\Templates;

use Illuminate\Config\Repository;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use NewUp\Templates\Generators\AuthorsGenerator;
use NewUp\Templates\Renderers\TemplateRenderer;

class Initializer {

    /**
     * The template name.
     *
     * @var string
     */
    protected $templateName = '';

    protected $vendor; protected $package;

    /**
     * The template directory.
     *
     * @var string
     */
    protected $templateDirectory = '';

    /**
     * The template description.
     *
     * @var string
     */
    protected $templateDescription = '';

    /**
     * Whether or not to create a 'composer.json' file.
     *
     * @var bool
     */
    protected $createComposerFile = true;

    /**
     * The template license.
     *
     * @var string
     */
    protected $templateLicense = '';

    /**
     * The configuration writer instance.
     *
     * @var \NewUp\Templates\ConfigurationWriter
     */
    protected $writer;

    protected $config;

    protected $files;

    public function __construct(ConfigurationWriter $writer, Repository $config, Filesystem $files)
    {
        $this->writer = $writer;
        $this->config = $config;
        $this->files = $files;
    }

    /**
     * Gets the template name.
     *
     * @return string
     */
    public function getTemplateName()
    {
        return $this->templateName;
    }

    /**
     * Sets the template name.
     *
     * @param string $templateName
     */
    public function setTemplateName($templateName)
    {
        $this->validateTemplateName($templateName);
        $this->templateName = $templateName;
    }

    /**
     * Gets the template directory.
     *
     * @return string
     */
    public function getTemplateDirectory()
    {
        return $this->templateDirectory;
    }

    /**
     * Sets the template directory.
     *
     * @param string $templateDirectory
     */
    public function setTemplateDirectory($templateDirectory)
    {
        $this->validateDirectory($templateDirectory);
        $this->templateDirectory = $templateDirectory;
    }

    /**
     * Indicates whether or not a composer.json file will be created.
     *
     * @return boolean
     */
    public function isCreateComposerFile()
    {
        return $this->createComposerFile;
    }

    /**
     * Sets whether or not a composer.json file will be created.
     *
     * @param boolean $createComposerFile
     */
    public function setCreateComposerFile($createComposerFile)
    {
        $this->createComposerFile = $createComposerFile;
    }

    /**
     * Gets the template description.
     *
     * @return string
     */
    public function getTemplateDescription()
    {
        return $this->templateDescription;
    }

    /**
     * Sets the template description.
     *
     * @param string $templateDescription
     */
    public function setTemplateDescription($templateDescription)
    {
        $this->templateDescription = $templateDescription;
    }

    /**
     * Validates the template name.
     *
     * @param $templateName
     * @throws InitializerException
     */
    private function validateTemplateName($templateName)
    {
        $templateNameParts = explode('/', $templateName);

        if (count($templateNameParts) !== 2)
        {
            throw new InitializerException('The package name is not valid.');
        }

        if (strlen($templateNameParts[0]) == 0 || strlen($templateNameParts[1]) == 0)
        {
            throw new InitializerException('The package name is not valid.');
        }

        $this->vendor = $templateNameParts[0];
        $this->package = $templateNameParts[1];
    }

    /**
     * Gets the template license.
     *
     * @return string
     */
    public function getTemplateLicense()
    {
        return $this->templateLicense;
    }

    /**
     * Sets the template license.
     *
     * @param string $templateLicense
     */
    public function setTemplateLicense($templateLicense)
    {
        $this->templateLicense = $templateLicense;
    }

    /**
     * Validates the template directory.
     *
     * @param $directory
     * @throws InitializerException
     */
    private function validateDirectory($directory)
    {
        if (!is_writable($directory))
        {
            throw new InitializerException($directory . ' is not writable.');
        }

        if (!is_readable($directory))
        {
            throw new InitializerException($directory . ' is not readable.');
        }
    }

    private function writerComposerFile($configFile)
    {
        $this->writer->put('name', $this->templateName);
        $this->writer->put('description', $this->templateDescription);
        $this->writer->put('tags', ['newup', 'template', 'package']);
        $this->writer->put('license', $this->templateLicense);
        $this->writer->put('authors', AuthorsGenerator::generate($this->config->get('authorship.authors', [])));
        $this->writer->save($configFile);
        $this->writer->reset();
    }

    private function writeNewUpFile($configFile)
    {
        $this->writer->put('templateName', $this->templateName);
        $this->writer->put('description', $this->templateDescription);
        $this->writer->put('license', $this->templateLicense);
        $this->writer->put('authors', AuthorsGenerator::generate($this->config->get('authorship.authors', [])));
        $this->writer->save($configFile);
        $this->writer->reset();
    }

    private function createNewUpDirectories()
    {
        $this->files->makeDirectory($this->templateDirectory.'/_newup', 0755, false, true);
        $this->files->makeDirectory($this->templateDirectory.'/v1', 0755, false, true);
    }

    /**
     * Initializes the template directory.
     *
     *
     */
    public function initialize()
    {
        $this->createNewUpDirectories();
        $this->writeNewUpFile(realpath($this->templateDirectory) . '/newup.json');
        $renderer = new TemplateRenderer();

        $renderer->setData('package', $this->package);
        $renderer->setData('vendor', $this->vendor);
        $renderer->setData('license', $this->templateLicense);
        $renderer->setData('description', $this->templateDescription);

        $this->files->put(realpath($this->templateDirectory).'/_newup/'.Str::studly($this->package).'Template.php', $renderer->render('template'));
        if ($this->createComposerFile)
        {
            $this->writerComposerFile(realpath($this->templateDirectory) . '/composer.json');
        }

        return true;
    }

}