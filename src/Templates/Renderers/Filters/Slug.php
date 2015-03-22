<?php namespace NewUp\Templates\Renderers\Filters;

use Illuminate\Support\Str;

class Slug extends Filter {

    protected $name = 'slug';

    public function getOperator()
    {
        return function ($string)
        {
            return Str::slug($string);
        };
    }


}