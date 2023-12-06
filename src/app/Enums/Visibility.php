<?php

namespace Zerotoprod\ModelCodegen\Enums;

/**
 * @property string $value
 * @property string $name
 **/
enum Visibility: string
{
    case public = 'public';
    case protected = 'protected';
    case private = 'private';
}
