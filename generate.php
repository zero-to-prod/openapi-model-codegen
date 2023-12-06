<?php

require_once __DIR__ . '/vendor/autoload.php';

try {
    $data = json_decode(file_get_contents($argv[1]), true);
    $schemas = $data['definitions'] ?? $data['components']['schemas'] ?? null;

    if (!$schemas) {
        echo 'Could not find models definitions in the provided file.';

        exit;
    }

    generate(schemas: $schemas, save_path: $argv[2], namespace: $argv[3] ?? null);
} catch (Throwable $e) {
    echo $e->getMessage();

    exit;
}