<?php namespace NewUp\Console;


class Kernel extends BaseKernel {


    protected $commands = [
        'NewUp\Console\Commands\About',
        'NewUp\Console\Commands\Init',
        'NewUp\Console\Commands\Builder',
        'NewUp\Console\Commands\ListTemplates',
        'NewUp\Console\Commands\UpdateSystem',
        'NewUp\Console\Commands\UpdateTemplate',
        'NewUp\Console\Commands\RemoveTemplate',
        'NewUp\Console\Commands\CheckTemplate',
        'NewUp\Console\Commands\InstallTemplate',
    ];


}