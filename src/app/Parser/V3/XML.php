<?php

namespace Zerotoprod\ModelCodegen\Parser\V3;

use Zerotoprod\ServiceModel\ServiceModel;

class XML
{
    use ServiceModel;

    public readonly ?string $name;
    public readonly ?string $namespace;
    public readonly ?string $prefix;
    public readonly ?bool $attribute;
    public readonly ?bool $wrapped;
}
