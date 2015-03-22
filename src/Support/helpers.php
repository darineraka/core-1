<?php

if (!function_exists('core_templates_path'))
{
    function core_templates_path()
    {
        return base_path().'/templates/system/core/';
    }
}

if (!function_exists('load_system_template'))
{
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