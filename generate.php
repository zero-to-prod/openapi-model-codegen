<?php

require_once __DIR__ . '/vendor/autoload.php';

$document = file_get_contents($argv[1]);
$start = microtime(true);
generate(
    document: $document,
    save_path: $argv[2],
    namespace: $argv[3] ?? null
);

echo 'Generated in: ' . number_format(microtime(true) - $start, 3) . ' seconds.' . PHP_EOL;