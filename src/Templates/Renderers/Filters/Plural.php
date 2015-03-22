<?php namespace NewUp\Templates\Renderers\Filters;

use Illuminate\Support\Str;

class Plural extends Filter {

    protected $name = 'plural';

    public function getOperator()
    {
        return function ($string)
        {
            return Str::plural($string);
        };
    }


}