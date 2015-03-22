<?php

if (!function_exists('core_templates_path'))
{
    /**
     * Gets the core templates path.
     *
     * @return string
     */
    function core_templates_path()
    {
        return base_path().'/templates/system/core/';
    }
}

if (!function_exists('load_system_template'))
{
    /**
     * Get the contents of a system template by name.
     *
     * @param $templateName
     * @return null|string
     */
    function load_system_template($templateName)
    {
        $templateFile = base_path().'/templates/system/'.$templateName.'.newup';

        if (file_exists($templateFile))
        {
            return file_get_contents($templateFile);
        }

        return null;
    }
}

if (!function_exists('load_core_template'))
{
    /**
     * Get the contents of a core template by name.
     *
     * @param $templateName
     * @return null|string
     */
    function load_core_template($templateName)
    {
        $templateFile = base_path().'/templates/system/core/'.$templateName;

        if (file_exists($templateFile))
        {
            return file_get_contents($templateFile);
        }

        return null;
    }
}