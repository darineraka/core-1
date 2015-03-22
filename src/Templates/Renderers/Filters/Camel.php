<?php namespace NewUp\Templates\Renderers\Filters;

use Illuminate\Support\Str;

class Camel extends Filter {

    protected $name = 'camel';

    public function getOperator()
    {
        return function ($string)
        {
            return Str::camel($string);
        };
    }


}