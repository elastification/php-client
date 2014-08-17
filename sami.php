<?php
use Sami\Sami;
use Symfony\Component\Finder\Finder;

$iterator = Finder::create()
    ->files()
    ->in('src');

return new Sami(
    $iterator, array(
        'title' => 'Elastification PHP Client API',
        'build_dir' => __DIR__ . '/docs',
        'cache_dir' => __DIR__ . '/cache',
        'default_opened_level' => 2,
    )
);
