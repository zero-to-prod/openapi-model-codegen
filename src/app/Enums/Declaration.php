<?php

namespace Zerotoprod\ModelCodegen\Enums;

/**
 * @property string $value
 * @property string $name
 **/
enum Declaration: string
{
    case null = 'null';
    case bool = 'bool';
    case int = 'int';
    case float = 'float';
    case string = 'string';
    case array = 'array';
    case object = 'object';
    case resource = 'resource';
    case mixed = 'mixed';
}
