<?php

namespace Zerotoprod\ModelCodegen\Parser\V3;

use Zerotoprod\ModelCodegen\Casters\CastToSchema;
use Zerotoprod\ServiceModel\ServiceModel;

class Schema
{
    use ServiceModel;

    public const type = 'type';
    public const enum = 'enum';
    public const _ref = '_ref';
    public readonly ?string $title;
    public readonly ?string $multipleOf;
    public readonly ?string $maximum;
    public readonly ?string $exclusiveMaximum;
    public readonly ?string $minimum;
    public readonly ?string $exclusiveMinimum;
    public readonly ?string $maxLength;
    public readonly ?string $minLength;
    public readonly ?string $pattern;
    public readonly ?string $maxItems;
    public readonly ?string $minItems;
    public readonly ?string $uniqueItems;
    public readonly ?string $maxProperties;
    public readonly ?string $minProperties;
    public readonly ?array $required;
    public readonly ?array $enum;
    public readonly ?string $type;
    /* @var Schema|Reference|null */
    public readonly mixed $allOf;
    /* @var Schema|Reference|null */
    public readonly mixed $oneOf;
    /* @var Schema|Reference|null */
    public readonly mixed $anyOf;
    /* @var Schema|Reference|null */
    public readonly mixed $not;
    /* @var Schema[]|Reference[] */
    #[CastToSchema(Schema::class)]
    public readonly mixed $items;
    /* @var Schema[]|Reference */
    #[CastToSchema(Schema::class)]
    public readonly mixed $properties;
    /* @var Schema|Reference|bool|null */
    public readonly mixed $additionalProperties;
    public readonly ?string $description;
    public readonly mixed $format;
    public readonly mixed $default;
    public readonly ?bool $nullable;
    public readonly ?Discrimiator $discriminator;
    public readonly ?bool $readOnly;
    public readonly ?bool $writeOnly;
    public readonly ?XML $xml;
    public readonly ?ExternalDocumentation $externalDocs;
    public readonly string $example;
    public readonly ?bool $deprecated;
    public readonly ?Reference $_ref;

    public function nullable(): bool
    {
        return $this->nullable ?? false;
    }
}
