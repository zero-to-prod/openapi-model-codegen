<?php

require_once __DIR__ . '/vendor/autoload.php';

generate(
    schemas: json_decode(file_get_contents($argv[1]), true)['components']['schemas'],
    save_path: $argv[2],
    namespace: $argv[3] ?? null
);