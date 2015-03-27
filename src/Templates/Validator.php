<?php namespace NewUp\Templates;

use Illuminate\Support\Str;

class Validator {

    protected $errors = [];

    /**
     * Validates a package template.
     *
     * @param $directory
     * @return bool
     */
    public function validateTemplate($directory)
    {
        $directory = realpath($directory);

        // Null check.
        if ($directory == null)
        {
            $this->errors[] = $directory.' is not a valid template directory.';
            return false;
        }

        // Existence check.
        if (!file_exists($directory))
        {
            $this->errors[] = $directory.' does not exist.';
            return false;
        }

        // Newup.json exists
        if (!$this->validateNewupJsonFile($directory))
        {
            $this->errors[] = 'Invalid newup.json file or processor setup detected.';
            return false;
        }


        return true;
    }

    private function validateNewupProcessor($directory, $templateName)
    {
        $templateName = Str::studly($templateName);
        $processorFile = $directory.$templateName.'Template.php';

        // Existence check.
        if (!file_exists($processorFile))
        {
            $this->errors[] = 'Processor file missing: '.$processorFile;
            $this->errors[] = '==Invalid Template Processor==';
            return false;
        }

        // Valid class test.
        try
        {
            require_once $processorFile;
            $className = $templateName.'Template';
            $processor = new $className;

            $rootPath = realpath($directory.'../');

            // Check that all the variation directories exist.
            foreach ($processor->variations() as $variation)
            {
                if (!file_exists($rootPath.'/'.$variation[0]) && !is_dir($rootPath.'/'.$variation[0]))
                {
                    $this->errors[] = 'Variation '.$variation[0].' directory does not exist (as defined in the processor).';
                    return false;
                }
            }


        } catch (\Exception $e)
        {
            $this->errors[] = $e->getMessage();
            $this->errors[] = '==Unkown processor error. Message given:==';
            return false;
        }


        return true;
    }

    public function validateNewupJsonFile($directory)
    {
        $newupFile = $directory . '/newup.json';
        $newupDir  = $directory . '/_newup/';

        // Check for JSON file.
        if (!file_exists($newupFile))
        {
            $this->errors[] = 'Invalid "newup.json" file. '.$directory.' does not exist.';
            return false;
        }
        if (!$this->validateJsonFile($newupFile))
        {
            $this->errors[] = 'Invalid "newup.json" file. '.$directory.' is not valid JSON.';
            return false;
        }

        // Check for directory.
        if (!file_exists($newupDir) && !is_dir($newupDir))
        {

            $this->errors[] = 'Invalid NewUp format. "_newup" directory missing.';
            return false;
        }

        // Check for template name and description. We can do this by simply creating a package object, since it
        // throws a lot of exceptions if things don't work out so well.
        $package = Package::fromFile($newupFile);

        if (!$this->validateNewupProcessor($newupDir, $package->getPackage())) { return false; }


        return true;
    }


    private function validateJsonFile($file)
    {
        json_decode(file_get_contents($file));

        return (json_last_error() == JSON_ERROR_NONE);
    }

    public function getErrors()
    {
        return $this->errors;
    }

}