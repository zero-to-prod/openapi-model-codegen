<?php

use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Factory;
use Illuminate\View\FileViewFinder;
use Zerotoprod\ModelCodegen\Generators\Enums\EnumController;
use Zerotoprod\ModelCodegen\Generators\Models\ClassController;
use Zerotoprod\ModelCodegen\Models\ClassDto;
use Zerotoprod\ModelCodegen\Models\EnumDto;
use Zerotoprod\ModelCodegen\Parser\V3\Schema;

if (!function_exists('view')) {
    function view($view, array $variables = [], array|string $view_paths = []): string
    {
        $Filesystem = new Filesystem;
        $top_level_dir = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR;

        return (new Factory(
            tap(new EngineResolver, static fn(EngineResolver $EngineResolver) => $EngineResolver->register('blade', fn() => new CompilerEngine(
                new BladeCompiler($Filesystem, $top_level_dir
                    . 'storage'
                    . DIRECTORY_SEPARATOR
                    . 'framework'
                    . DIRECTORY_SEPARATOR
                    . 'views'
                )
            ))),
            new FileViewFinder(new Filesystem, array_merge(
                    [$top_level_dir . 'resources' . DIRECTORY_SEPARATOR . 'views'],
                    is_string($view_paths) ? [$view_paths] : $view_paths)
            ),
            new Dispatcher(new Container))
        )->make($view, $variables)->render();
    }
}

if (!function_exists('to_valid_identifier')) {
    function to_valid_identifier(?string $string): ?string
    {
        if (is_null($string)) {
            return null;
        }

        $reserved_words = [
            "abstract", "and", "array", "as", "break", "callable", "case", "catch", "class", "clone", "const", "continue",
            "declare", "default", "die", "do", "echo", "else", "elseif", "empty", "enddeclare", "endfor", "endforeach",
            "endif", "endswitch", "endwhile", "eval", "exit", "extends", "final", "finally", "fn", "for", "foreach",
            "function", "global", "goto", "if", "implements", "include", "include_once", "instanceof", "insteadof",
            "interface", "isset", "list", "match", "namespace", "new", "or", "print", "private", "protected", "public",
            "readonly", "require", "require_once", "return", "static", "switch", "throw", "trait", "try", "unset", "use",
            "var", "while", "xor", "yield"
        ];

        // Replace non-alphanumeric characters with underscores
        $identifier = preg_replace('/\W/', '_', $string);

        // Ensure the first character is a letter or underscore
        if ($identifier[0] !== '_' && !ctype_alpha($identifier[0])) {
            $identifier = '_' . $identifier;
        }

        if (in_array(strtolower($identifier), $reserved_words)) {
            $identifier = '_' . $identifier;
        }

        return $identifier;
    }
}

if (!function_exists('path_to_namespace')) {
    function path_to_namespace($path): string
    {
        // Remove './' at the beginning and any trailing slashes
        $path = preg_replace('/^\.\//', '', rtrim($path, '/\\'));
        $segments = array_map('ucfirst', explode('/', $path));

        return implode('\\', $segments);
    }
}

if (!function_exists('generate')) {
    /**
     * @param string $document
     * @param string $save_path
     * @param string|null $namespace
     * @return void
     */
    function generate(string $document, string $save_path, ?string $namespace): void
    {
        $schema_collection = collect(json_decode($document, true)['components']['schemas']);
        if (!is_dir($save_path) && !mkdir($save_path, 0777, true) && !is_dir($save_path)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $save_path));
        }

        foreach ($schema_collection->filter(fn(array $schema) => isset($schema['properties'])) as $key => $schema) {
            foreach ($schema['properties'] as $property => $value) {
                if (isset($value['enum'])) {
                    $enumDto = EnumDto::make([
                        EnumDto::classname => $property,
                        EnumDto::values => $value['enum'],
                    ]);

                    $data = EnumController::make(
                        namespace: $namespace,
                        classname: $enumDto->classname,
                        values: $enumDto->values
                    )->render();

                    if ($data) {
                        file_put_contents($enumDto->toFilename($save_path), $data);
                    }
                }
                if (isset($value['items']['enum'])) {
                    $enumDto = EnumDto::make([
                        EnumDto::classname => $property,
                        EnumDto::values => $value['items']['enum'],
                    ]);

                    $data = EnumController::make(
                        namespace: $namespace,
                        classname: $enumDto->classname,
                        values: $enumDto->values
                    )->render();

                    if ($data) {
                        file_put_contents($enumDto->toFilename($save_path), $data);
                    }
                }
            }
            $classDto = ClassDto::make([
                ClassDto::classname => $key,
                ClassDto::properties => Schema::make($schema)->properties,
            ]);

            $data = ClassController::make(
                schema: $schema_collection->toArray(),
                classname: $classDto->classname,
                properties: $classDto->properties,
                namespace: $namespace
            )->render();

            if ($data) {
                file_put_contents($classDto->toFilename($save_path), $data);
            }
        }
    }
}