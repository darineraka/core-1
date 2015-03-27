<?php namespace NewUp\Templates\Renderers\Filters;

use Illuminate\Support\Str;

class SlugFilter extends Filter {

    protected $name = 'slug';

    public function getOperator()
    {
        return function ($string, $separator = '-')
        {
            return Str::slug($string, $separator);
        };
    }


}