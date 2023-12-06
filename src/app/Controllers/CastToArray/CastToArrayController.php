<?php

namespace Zerotoprod\ModelCodegen\Controllers\CastToArray;

use Zerotoprod\ModelCodegen\Enums\Template;
use Zerotoprod\ModelCodegen\Models\Property;
use Zerotoprod\ModelCodegen\Support\Controller;

/**
 * @method static CastToArrayController make(Property $property)
 */
class CastToArrayController
{
    use Controller;

    public readonly Property $Property;

    public function handle(Property $property): self
    {
        $this->Property = $property;

        return $this;
    }

    public function render(): string
    {
        return view(Template::cast_to_array->value, ['Property' => $this->Property], __DIR__);
    }
}