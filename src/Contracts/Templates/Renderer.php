<?php namespace NewUp\Contracts\Templates;

interface Renderer {

    /**
     * Adds a path to the rendering environment.
     *
     * @param $path
     * @return void
     */
    public function addPath($path);

    /**
     * Gets the paths of the rendering environment.
     *
     * @return mixed
     */
    public function getPaths();

    /**
     * Gets the data array that the environment is using.
     *
     * @return array
     */
    public function getData();

    /**
     * Sets an environment variable.
     *
     * @param $key
     * @param $value
     * @return void
     */
    public function setData($key, $value);

    /**
     * Renders a template by template file name.
     *
     * The template must exist in one of the environment paths.
     *
     * @param $template
     * @return string
     */
    public function render($template);

    /**
     * Renders a string as a template.
     *
     * @param $string
     * @return string
     */
    public function renderString($string);

}