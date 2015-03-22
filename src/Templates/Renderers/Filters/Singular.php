<?php namespace NewUp\Templates\Renderers\Filters;

use Illuminate\Support\Str;

class Singular extends Filter {

    protected $name = 'singular';

    public function getOperator()
    {
        return function ($string)
        {
            return Str::singular($string);
        };
    }


}