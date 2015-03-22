<?php namespace NewUp\Templates\Renderers\Filters;

use Illuminate\Support\Str;

class Studly extends Filter {

    protected $name = 'studly';

    public function getOperator()
    {
        return function ($string)
        {
            return Str::studly($string);
        };
    }


}