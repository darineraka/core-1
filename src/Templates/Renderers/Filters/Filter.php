<?php namespace NewUp\Templates\Renderers\Filters;

use NewUp\Contracts\Filter as FilterContract;

abstract class Filter implements FilterContract {

    protected $name;


    public function getName()
    {
        return $this->name;
    }

}