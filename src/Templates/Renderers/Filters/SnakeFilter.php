<?php namespace NewUp\Templates\Renderers\Filters;

use Illuminate\Support\Str;

class SnakeFilter extends Filter
{

    protected $name = 'snake';

    public function getOperator()
    {
        return function ($string, $delimiter = '_') {
            return Str::snake($string, $delimiter);
        };
    }


}