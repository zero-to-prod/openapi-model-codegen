<?php

namespace Zerotoprod\ModelCodegen\Rules;

use Zerotoprod\ModelCodegen\Support\EvaluatesRule;

/**
 * @method static bool evaluate(array $openapi_schema)
 */
class CastsToArray
{
    use EvaluatesRule;

    public function handle(array $openapi_schema): bool
    {
        return collect($openapi_schema)
            ->contains(fn($property) => isset($property['type'], $property['items']['$ref']) && $property['type'] === 'array');
    }

}