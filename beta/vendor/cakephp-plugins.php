<?php
$baseDir = dirname(dirname(__FILE__));
return [
    'plugins' => [
        'AssetCompress' => $baseDir . '/vendor/markstory/asset_compress/',
        'Bake' => $baseDir . '/vendor/cakephp/bake/',
        'BootstrapUI' => $baseDir . '/vendor/friendsofcake/bootstrap-ui/',
        'Crud' => $baseDir . '/vendor/friendsofcake/crud/',
        'DebugKit' => $baseDir . '/vendor/cakephp/debug_kit/',
        'Josegonzalez/Upload' => $baseDir . '/vendor/josegonzalez/cakephp-upload/',
        'Migrations' => $baseDir . '/vendor/cakephp/migrations/',
        'MiniAsset' => $baseDir . '/vendor/markstory/mini-asset/',
        'Muffin/Slug' => $baseDir . '/vendor/muffin/slug/',
        'Search' => $baseDir . '/vendor/friendsofcake/search/',
        'Settings' => $baseDir . '/plugins/Settings/',
        'WyriHaximus/MinifyHtml' => $baseDir . '/vendor/wyrihaximus/minify-html/',
        'Yaml' => $baseDir . '/vendor/guemidiborhane/yaml-config/'
    ]
];