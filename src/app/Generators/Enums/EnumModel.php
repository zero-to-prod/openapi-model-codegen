<?php

namespace Zerotoprod\ModelCodegen\Generators\Enums;

use Zerotoprod\ModelCodegen\Support\Model;
use Zerotoprod\ServiceModel\ServiceModel;

class EnumModel extends Model
{
    use ServiceModel;

    public const namespace = 'namespace';
    public const classname = 'classname';
    public const values = 'values';
    public readonly ?string $namespace;
    public readonly string $classname;
    public readonly array $values;
}