<?php

return [
    'timezone'  => 'America/Chicago',
    'debug'     => true,

    'providers' => [
        'Illuminate\Filesystem\FilesystemServiceProvider',
        'NewUp\Templates\Renderers\RendererServiceProvider',
    ],

    'render_filters' => [
        'NewUp\Templates\Renderers\Filters\StudlyFilter',
        'NewUp\Templates\Renderers\Filters\CamelFilter',
        'NewUp\Templates\Renderers\Filters\LowerFilter',
        'NewUp\Templates\Renderers\Filters\PluralFilter',
        'NewUp\Templates\Renderers\Filters\SingularFilter',
        'NewUp\Templates\Renderers\Filters\SlugFilter',
        'NewUp\Templates\Renderers\Filters\SnakeFilter',
        'NewUp\Templates\Renderers\Filters\StudlyFilter',
        'NewUp\Templates\Renderers\Filters\UpperFilter',
    ]

];