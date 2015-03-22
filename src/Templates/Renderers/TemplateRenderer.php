<?php namespace NewUp\Templates\Renderers;

use NewUp\Contracts\Filter as FilterContract;
use NewUp\Contracts\Renderer;
use NewUp\Foundation\Application;

class TemplateRenderer implements Renderer {

    protected $twigFileLoader = null;

    protected $twigSystemLoader = null;

    protected $twigEnvironment = null;

    protected $twigStringEnvironment = null;

    protected $dataArray = [];

    public function __construct()
    {
        $this->twigFileLoader   = new \Twig_Loader_Filesystem(core_templates_path());
        $this->twigSystemLoader = new \Twig_Loader_Array([
                                                             'template' => load_system_template('TemplateClass')
                                                         ]);

        $this->twigEnvironment       =
            new \Twig_Environment(new \Twig_Loader_Chain([$this->twigSystemLoader, $this->twigFileLoader]));
        $this->twigStringEnvironment = new \Twig_Environment(new \Twig_Loader_String());
        $this->registerFilters();
    }

    /**
     * Registers the template filters.
     */
    private function registerFilters()
    {
        $filters = config('app.render_filters', []);

        foreach ($filters as $filter)
        {
            $filter = app($filter);

            if ($filter instanceof FilterContract)
            {
                $this->twigEnvironment->addFilter(new \Twig_SimpleFilter($filter->getName(), $filter->getOperator()));
                $this->twigStringEnvironment->addFilter(new \Twig_SimpleFilter($filter->getName(),
                                                                               $filter->getOperator()));
            }
        }
    }

    /**
     * Adds a path to the template environment.
     *
     * @param $path
     * @throws InvalidPathException
     */
    public function addPath($path)
    {
        try
        {
            $this->twigFileLoader->addPath($path);
        }
        catch (\Twig_Error_Loader $e)
        {
            throw new InvalidPathException($e->getMessage());
        }
    }

    /**
     * Gets the paths of the rendering environment.
     *
     * @return mixed
     */
    public function getPaths()
    {
        return $this->twigFileLoader->getPaths();
    }


    /**
     * Returns the file loader.
     *
     * @return null|\Twig_Loader_Filesystem
     */
    public function getLoader()
    {
        return $this->twigFileLoader;
    }

    /**
     * Returns the underlying Twig environment.
     *
     * @return null|\Twig_Environment
     */
    public function getEnvironment()
    {
        return $this->twigEnvironment;
    }

    /**
     * Gets the environment data.
     *
     * @return array
     */
    public function getData()
    {
        $systemInformation                  = [];
        $systemInformation['newup_version'] = Application::VERSION;
        $systemInformation['newup_test']    = 'test-here';

        return $this->dataArray + $systemInformation;
    }

    /**
     * Sets an environment variable.
     *
     * @param $key
     * @param $value
     */
    public function setData($key, $value)
    {
        $this->dataArray[$key] = $value;
    }

    /**
     * Renders a template by name.
     *
     * @param $templateName
     * @return string
     * @throws InvalidTemplateException
     * @throws InvalidSyntaxException
     */
    public function render($templateName)
    {
        try
        {
            return $this->twigEnvironment->render($templateName, $this->getData());
        }
        catch (\Twig_Error_Syntax $e)
        {
            throw new InvalidSyntaxException($e->getMessage());
        }
        catch (\Twig_Error_Loader $e)
        {
            throw new InvalidTemplateException($e->getMessage());
        }
    }

    /**
     * Renders a string as a template.
     *
     * @param $templateString
     * @return string
     * @throws InvalidSyntaxException
     */
    public function renderString($templateString)
    {
        try
        {
            return $this->twigStringEnvironment->render($templateString, $this->getData());
        }
        catch (\Twig_Error_Syntax $e)
        {
            throw new InvalidSyntaxException($e->getMessage());
        }
    }


}