<?php namespace NewUp\Templates;

use Illuminate\Support\Collection;

class ConfigurationWriter extends Collection {

    public function reset()
    {
        $this->items = [];
    }

    /**
     * Saves the configuration values at the given path.
     *
     * @param $fileName
     */
    public function save($fileName)
    {
        file_put_contents($fileName, $this->toJson(JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    }

}