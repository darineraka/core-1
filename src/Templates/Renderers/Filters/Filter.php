<?php namespace NewUp\Templates\Renderers\Filters;

use NewUp\Contracts\Templates\Filter as FilterContract;

abstract class Filter implements FilterContract
{

    protected $name;


    public function getName()
    {
        return $this->name;
    }

}