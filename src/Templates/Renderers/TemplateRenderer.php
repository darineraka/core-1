<?php namespace NewUp\Templates\Renderers;

use NewUp\Contracts\Filter as FilterContract;
use NewUp\Contracts\Renderer;
use NewUp\Foundation\Application;

class TemplateRenderer implements Renderer {

    /**
     * Twig file system loader instance.
     *
     * @var null|\Twig_Loader_Filesystem
     */
    protected $twigFileLoader = null;

    /**
     * Twig array loader.
     *
     * Holds system templates (they are given a custom name).
     *
     * @var null|\Twig_Loader_Array
     */
    protected $twigSystemLoader = null;

    /**
     * The Twig environment instance.
     *
     * Used by the `render()` function.
     *
     * @var null|\Twig_Environment
     */
    protected $twigEnvironment = null;

    /**
     * The Twig environment instance.
     *
     * Used by the `renderString()` function.
     *
     * @var null|\Twig_Environment
     */
    protected $twigStringEnvironment = null;

    /**
     * Renderer environment variables.
     *
     * @var array
     */
    protected $dataArray = [];

    /**
     * The Twig environment options.
     *
     * @var array
     */
    protected $environmentOptions = [
        'autoescape' => false
    ];

    /**
     * Returns a new instance of TemplateRenderer
     */
    public function __construct()
    {
        $this->twigFileLoader   = new \Twig_Loader_Filesystem();
        $this->twigSystemLoader = new \Twig_Loader_Array([
                                                             'template' => load_system_template('TemplateClass')
                                                         ]);

        $this->twigEnvironment       =
            new \Twig_Environment(new \Twig_Loader_Chain([$this->twigSystemLoader, $this->twigFileLoader]),
                                  $this->environmentOptions);
        $this->twigStringEnvironment =
            new \Twig_Environment(new \Twig_Loader_Chain([$this->twigSystemLoader, $this->twigFileLoader,
                                                          new \Twig_Loader_String()]), $this->environmentOptions);

        $this->registerFilters();
        $this->registerCorePaths();
    }

    /**
     * Registers the core system paths.
     *
     * @throws InvalidPathException
     */
    private function registerCorePaths()
    {
        $this->addPath(core_templates_path());
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
     * @throws RuntimeException
     * @throws SecurityException
     */
    public function render($templateName)
    {
        try
        {
            return $this->twigEnvironment->render($templateName, $this->getData());
        }
        catch (SecurityException $e)
        {
            throw new SecurityException($e->getMessage(), $e->getCode(), $e);
        }
        catch (\Twig_Error_Runtime $e)
        {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }
        catch (\Twig_Error_Syntax $e)
        {
            throw new InvalidSyntaxException($e->getMessage(), $e->getCode(), $e);
        }
        catch (\Twig_Error_Loader $e)
        {
            throw new InvalidTemplateException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Renders a string as a template.
     *
     * @param $templateString
     * @return string
     * @throws InvalidTemplateException
     * @throws InvalidSyntaxException
     * @throws RuntimeException
     * @throws SecurityException
     */
    public function renderString($templateString)
    {
        try
        {
            return $this->twigStringEnvironment->render($templateString, $this->getData());
        }
        catch (SecurityException $e)
        {
            throw new SecurityException($e->getMessage(), $e->getCode(), $e);
        }
        catch (\Twig_Error_Runtime $e)
        {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }
        catch (\Twig_Error_Syntax $e)
        {
            throw new InvalidSyntaxException($e->getMessage(), $e->getCode(), $e);
        }
        catch (\Twig_Error_Loader $e)
        {
            throw new InvalidTemplateException($e->getMessage(), $e->getCode(), $e);
        }
    }


}