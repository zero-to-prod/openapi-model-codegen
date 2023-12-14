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
    public const comment = 'comment';
    public const default_value = 'default_value';
    public const value = 'value';
    public const doc_block_value = 'doc_block_value';

    public readonly Visibility $visibility;
    public readonly ?bool $readonly;
    public readonly array $declarations;
    #[Cast(ToClassname::class)]
    public readonly ?string $ref_classname;
    #[Cast(ToIdentifier::class)]
    public readonly string $name;
    public readonly Template $template;
    public readonly ?string $comment;
    public readonly ?string $default_value;
    public readonly ?string $value;
    public readonly ?string $doc_block_value;
}
