<?php

namespace Zerotoprod\ModelCodegen\Models;


use Zerotoprod\ModelCodegen\Casters\ToClassname;
use Zerotoprod\ModelCodegen\Casters\ToIdentifier;
use Zerotoprod\ModelCodegen\Enums\Template;
use Zerotoprod\ModelCodegen\Enums\Visibility;
use Zerotoprod\ServiceModel\Cast;
use Zerotoprod\ServiceModel\ServiceModel;

class Property
{
    use ServiceModel;

    public const visibility = 'visibility';
    public const readonly = 'readonly';
    public const declarations = 'declarations';
    public const ref_classname = 'ref_classname';
    public const name = 'name';
    public const template = 'template';

    public readonly Visibility $visibility;
    public readonly ?bool $readonly;
    public readonly array $declarations;
    #[Cast(ToClassname::class)]
    public readonly ?string $ref_classname;
    #[Cast(ToIdentifier::class)]
    public readonly string $name;
    public readonly Template $template;
}
