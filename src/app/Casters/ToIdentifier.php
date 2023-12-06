<?php

namespace Zerotoprod\ModelCodegen\Casters;

use Zerotoprod\ServiceModel\CanCast;

class ToIdentifier implements CanCast
{
    public function set(array $value): string
    {
        return to_valid_identifier($value[0]);
    }
}