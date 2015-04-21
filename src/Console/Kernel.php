<?php namespace NewUp\Console;


class Kernel extends BaseKernel
{

    protected $commands = [
        'NewUp\Console\Commands\About',
    ];

}