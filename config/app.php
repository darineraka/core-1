<?php

return [
    'timezone'  => 'America/Chicago',
    'debug'     => true,

    'providers' => [
        'Illuminate\Filesystem\FilesystemServiceProvider',
        'NewUp\Templates\Renderers\RendererServiceProvider',
    ],

    'render_filters' => [
        'NewUp\Templates\Renderers\Filters\Studly',
        'NewUp\Templates\Renderers\Filters\Camel',
        'NewUp\Templates\Renderers\Filters\Lower',
        'NewUp\Templates\Renderers\Filters\Plural',
        'NewUp\Templates\Renderers\Filters\Singular',
        'NewUp\Templates\Renderers\Filters\Slug',
        'NewUp\Templates\Renderers\Filters\Snake',
        'NewUp\Templates\Renderers\Filters\Studly',
        'NewUp\Templates\Renderers\Filters\Upper',
    ]

];