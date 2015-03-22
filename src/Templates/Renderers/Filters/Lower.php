<?php namespace NewUp\Templates\Renderers\Filters;

use Illuminate\Support\Str;

class Lower extends Filter {

    protected $name = 'lower';

    public function getOperator()
    {
        return function ($string)
        {
            return Str::lower($string);
        };
    }


}