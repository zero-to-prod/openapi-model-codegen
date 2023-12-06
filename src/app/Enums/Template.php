<?php

namespace Zerotoprod\ModelCodegen\Enums;

/**
 * @property string $value
 * @property string $name
 **/
enum Template: string
{
    case cast_to_array = 'cast_to_array';
    case enum = 'enum';
    case native = 'native';
}
