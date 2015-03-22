<?php namespace NewUp\Templates\Renderers\Filters;

use Illuminate\Support\Str;

class Upper extends Filter {

    protected $name = 'upper';

    public function getOperator()
    {
        return function ($string)
        {
            return Str::upper($string);
        };
    }


}