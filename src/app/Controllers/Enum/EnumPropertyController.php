<?php

namespace Zerotoprod\ModelCodegen\Controllers\Enum;

use Zerotoprod\ModelCodegen\Enums\Template;
use Zerotoprod\ModelCodegen\Models\Property;
use Zerotoprod\ModelCodegen\Support\Controller;

/**
 * @method static EnumPropertyController make(Property $property)
 */
class EnumPropertyController
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
        return view(Template::enum->value, ['Property' => $this->Property], __DIR__);
    }
}