<?php

require __DIR__ . '/vendor/autoload.php';

use Kirby\Cms\App as Kirby;

echo (new Kirby([
  'roots' => [
    'index' => __DIR__,
    'base' => __DIR__,
    'blueprints' => __DIR__ . '/blueprints',
    'config' => __DIR__ . '/config',
    'templates' => __DIR__ . '/templates',
  ]
]))->render();
