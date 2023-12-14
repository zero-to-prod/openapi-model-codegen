<?php


namespace Zerotoprod\ModelCodegen\Parser;


final class DataTypes
{
    public const integer = 'integer';
    public const long = 'long';
    public const float = 'float';
    public const double = 'double';
    public const string = 'string';
    public const byte = 'byte';
    public const binary = 'binary';
    public const boolean = 'boolean';
    public const date = 'date';
    public const dateTime = 'dateTime';
    public const password = 'password';

    public const number = 'number';
    public const int32 = 'int32';
    public const int64 = 'int64';
    public const date_time = 'date-time';
    public const array = 'array';
    public const int = 'int';
    public const bool = 'bool';
    public const mixed = 'mixed';

    public const types = [
        self::integer => self::int,
        self::long => self::float,
        self::float => self::float,
        self::double => self::float,
        self::string => self::string,
        self::byte => self::string,
        self::binary => self::string,
        self::boolean => self::bool,
        self::date => self::string,
        self::dateTime => self::string,
        self::password => self::string,
        self::array => self::array,

        self::number => self::float,
        self::int32 => self::integer,
        self::int64 => self::integer,
        self::date_time => self::string,
    ];

    public static function get(string $type): string
    {
        if (!array_key_exists($type, self::types)) {
            return self::mixed;
        }

        if (self::int64 === $type && PHP_INT_SIZE < 8) {
            return self::string;
        }

        return self::types[$type];
    }
}