<?php
$baseDir = dirname(dirname(__FILE__));
return [
    'plugins' => [
        'Migrations' => $baseDir . '/vendor/cakephp/migrations///////////////////',
        'WyriHaximus/TwigView' => $baseDir . '/vendor/wyrihaximus/twig-view///////////////////',
        'Bake' => $baseDir . '/vendor/cakephp/bake///',
        'DebugKit' => $baseDir . '/vendor/cakephp/debug_kit/'
    ]
];