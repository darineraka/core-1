<?php

if (!function_exists('array_remove_value')) {
    /**
     * Removes the given value from the array.
     *
     * @param $array
     * @param $value
     */
    function array_remove_value(&$array, $value)
    {
        if (($key = array_search($value, $array)) !== false) {
            unset($array[$key]);
        }
    }
}

if (!function_exists('core_templates_path')) {
    /**
     * Gets the core templates path.
     *
     * @return string
     */
    function core_templates_path()
    {
        return storage_path() . '/templates/system/core/';
    }
}

if (!function_exists('load_system_template')) {
    /**
     * Get the contents of a system template by name.
     *
     * @param $templateName
     * @return null|string
     */
    function load_system_template($templateName)
    {
        $templateFile = storage_path() . '/templates/system/' . $templateName . '.newup';

        if (file_exists($templateFile)) {
            return file_get_contents($templateFile);
        }

        return null;
    }
}

if (!function_exists('load_core_template')) {
    /**
     * Get the contents of a core template by name.
     *
     * @param $templateName
     * @return null|string
     */
    function load_core_template($templateName)
    {
        $templateFile = storage_path() . '/templates/system/core/' . $templateName;

        if (file_exists($templateFile)) {
            return file_get_contents($templateFile);
        }

        return null;
    }
}