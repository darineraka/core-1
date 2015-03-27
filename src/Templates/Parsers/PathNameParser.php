<?php namespace NewUp\Templates\Parsers;

class PathNameParser {

    /**
     * Returns the variables stored within the path name.
     *
     * @param $pathName
     * @return array
     */
    public function getVariables($pathName)
    {
        $temporaryVariables = [];
        preg_match_all("/#(.*?)#/", $pathName, $temporaryVariables);

        if (count($temporaryVariables) > 0)
        {
            if (count($temporaryVariables[0]) > 0)
            {
                return $temporaryVariables[0];
            }
        }

        return [];
    }

    public function processVariable($variable)
    {
        if ($variable == '##')
        {
            return '#';
        }

        $variable = str_replace('[', '|', $variable);
        $variable = str_replace('#', '', $variable);
        $variable = '{{ '.$variable.' }}';

        return $variable;
    }

    public function processVariableArray($variables)
    {
        $newVariables = [];

        foreach ($variables as $key => $variable)
        {
            $variable = $this->processVariable($variable);
            $newVariables[$key] = $variable;
        }

        return $newVariables;
    }

    public function parse($name)
    {
        $originalVariables = $this->getVariables($name);
        $processedVariables = $this->processVariableArray($originalVariables);
        $replacementArray = array_combine($originalVariables, $processedVariables);

        return $replacementArray;
    }

}