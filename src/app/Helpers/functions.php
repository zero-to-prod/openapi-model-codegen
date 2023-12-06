<?php

use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Factory;
use Illuminate\View\FileViewFinder;
use Zerotoprod\ModelCodegen\Generators\Enums\EnumController;
use Zerotoprod\ModelCodegen\Generators\Models\ClassController;
use Zerotoprod\ModelCodegen\Models\ClassDto;
use Zerotoprod\ModelCodegen\Models\EnumDto;

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
    function generate(array $schemas, string $save_path, ?string $namespace): void
    {
        $schema_collection = collect($schemas);

        $schema_collection->filter(fn(array $schema) => isset($schema['enum']))
            ->reduce(fn(Collection $enums, array $schema, string $key) => $enums->push(
                EnumDto::make([
                    EnumDto::classname => $key,
                    EnumDto::values => $schema['enum'],
                ])), initial: collect())
            ?->each(fn(EnumDto $EnumDto) => file_put_contents(
                filename: $EnumDto->toFilename($save_path),
                data: EnumController::make(
                    namespace: $namespace,
                    classname: $EnumDto->classname,
                    values: $EnumDto->values
                )->render()
            ));

        $schema_collection->filter(fn(array $schema) => $schema['type'] === 'object' && isset($schema['properties']))
            ->reduce(fn(Collection $enums, array $schema, string $key) => $enums->push(
                ClassDto::make([
                    ClassDto::classname => $key,
                    ClassDto::properties => $schema['properties'],
                ])), initial: collect())
            ?->each(fn(ClassDto $ClassDto) => file_put_contents(
                filename: $ClassDto->toFilename($save_path),
                data: ClassController::make(
                    classname: $ClassDto->classname,
                    properties: $ClassDto->properties,
                    namespace: $namespace
                )->render()
            ));
    }
}