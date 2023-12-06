<?php

namespace Zerotoprod\ModelCodegen\Casters;

use Zerotoprod\ServiceModel\CanCast;

class ToClassname implements CanCast
{
    public function set(array $value): ?string
    {
        if (!$value) {
            return null;
        }
        return ucfirst(to_valid_identifier($value[0]));
    }
}