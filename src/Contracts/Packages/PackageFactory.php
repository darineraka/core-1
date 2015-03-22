<?php namespace NewUp\Contracts\Packages;

interface PackageFactory {

    /**
     * Returns a new package instance from the provided details.
     *
     * @param array $details
     * @return Package
     */
    public static function fromDetails(array $details);

    /**
     * Returns a new package instance from the provided file.
     *
     * The file must be valid JSON.
     *
     * @param $path
     * @return Package
     */
    public static function fromFile($path);

}