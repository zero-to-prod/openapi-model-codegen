<?php

namespace Zerotoprod\ModelCodegen\Models;


use Zerotoprod\ModelCodegen\Casters\ToClassname;
use Zerotoprod\ModelCodegen\Parser\V3\Schema;
use Zerotoprod\ServiceModel\Cast;
use Zerotoprod\ServiceModel\ServiceModel;

class ClassDto
{
    use ServiceModel;

    public const classname = 'classname';
    public const properties = 'properties';

    #[Cast(ToClassname::class)]
    public readonly string $classname;
    /* @var Schema[] $properties*/
    public readonly array $properties;

    public function toFilename(string $base_path): string
    {
        return $base_path . DIRECTORY_SEPARATOR . "$this->classname.php";
    }
}
