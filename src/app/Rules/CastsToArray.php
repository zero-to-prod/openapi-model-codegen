<?php

namespace Zerotoprod\ModelCodegen\Rules;

use Zerotoprod\ModelCodegen\Parser\V3\Reference;
use Zerotoprod\ModelCodegen\Parser\V3\Schema;
use Zerotoprod\ModelCodegen\Support\EvaluatesRule;

/**
 * @method static bool evaluate(array $openapi_schema)
 */
class CastsToArray
{
    use EvaluatesRule;

    /**
     * @param Schema[] $openapi_schema
     * @return bool
     */
    public function handle(array|Reference $openapi_schema): bool
    {
        return collect($openapi_schema)
            ->contains(function (Schema|Reference $property) {
                if ($property instanceof Reference) {
                    return true;
                }
                return isset($property->type, $property->items['$ref']->_ref) && $property->type === 'array';
            });
    }

}