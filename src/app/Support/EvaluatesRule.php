<?php

namespace Zerotoprod\ModelCodegen\Support;

trait EvaluatesRule
{
    public static function evaluate(...$args): bool
    {
        return (new self())->handle(...$args);
    }
}
