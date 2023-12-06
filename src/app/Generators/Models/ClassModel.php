<?php

namespace Zerotoprod\ModelCodegen\Generators\Models;

use Zerotoprod\ModelCodegen\Models\Property;
use Zerotoprod\ModelCodegen\Support\Model;
use Zerotoprod\ServiceModel\CastToArray;
use Zerotoprod\ServiceModel\ServiceModel;

class ClassModel extends Model
{
    use ServiceModel;

    public const namespace = 'namespace';
    public const classname = 'classname';
    public const imports = 'imports';
    public const traits = 'traits';
    public const properties = 'properties';
    public readonly ?string $namespace;
    public readonly string $classname;
    public readonly array $imports;
    public readonly array $traits;
    /**
     * @var Property[] $properties
     */
    #[CastToArray(Property::class)]
    public readonly array $properties;
}