<?php

namespace Zerotoprod\ModelCodegen\Models;


use Zerotoprod\ModelCodegen\Casters\ToClassname;
use Zerotoprod\ServiceModel\Cast;
use Zerotoprod\ServiceModel\ServiceModel;

class EnumDto
{
    use ServiceModel;

    public const classname = 'classname';
    public const values = 'values';

    #[Cast(ToClassname::class)]
    public readonly string $classname;
    /**
     * @var string[]
     */
    public readonly array $values;

    public function toFilename(string $base_path): string
    {
        return $base_path . DIRECTORY_SEPARATOR . "$this->classname.php";
    }
}
