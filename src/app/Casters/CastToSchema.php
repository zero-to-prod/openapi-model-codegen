<?php /** @noinspection PhpUndefinedMethodInspection */

namespace Zerotoprod\ModelCodegen\Casters;

use Attribute;
use Zerotoprod\ModelCodegen\Parser\V3\Reference;
use Zerotoprod\ServiceModel\CanCast;

#[Attribute]
class CastToSchema implements CanCast
{
    public function __construct(public readonly string $class)
    {
    }

    public function set(array $value): array|Reference
    {
        $results = [];

        foreach ($value as $key => $item) {
            if ($key === '$ref' || isset($item['$ref'])) {
                $results[$key] = Reference::make([Reference::_ref => $this->extractClassName($key === '$ref' ? $item : $item['$ref'])]);
            } else if (isset($item['items']['enum'])) {
                $results[$key] = $this->class::make($item['items']);
            } else {
                $results[$key] = $this->class::make($item);
            }
        }

        return $results;
    }

    private function extractClassName(?string $ref)
    {
        if (!$ref) {
            return null;
        }

        $parts = explode('/', $ref);

        return end($parts);
    }
}