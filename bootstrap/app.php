<?php

$app = new NewUp\Foundation\Application(realpath(__DIR__.'/../'));

$app->singleton(
'Illuminate\Contracts\Console\Kernel',
    'NewUp\Console\Kernel'
);

$app->singleton(
    'Illuminate\Contracts\Debug\ExceptionHandler',
    'NewUp\Exceptions\Handler'
);

return $app;