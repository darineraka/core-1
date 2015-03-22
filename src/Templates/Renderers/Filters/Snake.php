<?php namespace NewUp\Templates\Renderers\Filters;

use Illuminate\Support\Str;

class Snake extends Filter {

    protected $name = 'snake';

    public function getOperator()
    {
        return function ($string)
        {
            return Str::snake($string);
        };
    }


}