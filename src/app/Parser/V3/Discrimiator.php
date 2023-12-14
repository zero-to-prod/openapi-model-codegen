<?php

namespace Zerotoprod\ModelCodegen\Parser\V3;

use Zerotoprod\ServiceModel\ServiceModel;

class Discrimiator
{
    use ServiceModel;

    public readonly string $propertyName;
    public readonly ?array $mapping;
}
