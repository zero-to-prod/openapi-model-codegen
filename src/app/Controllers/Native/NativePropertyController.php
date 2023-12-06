<?php

namespace Zerotoprod\ModelCodegen\Controllers\Native;

use Zerotoprod\ModelCodegen\Enums\Template;
use Zerotoprod\ModelCodegen\Models\Property;
use Zerotoprod\ModelCodegen\Support\Controller;

/**
 * @method static NativePropertyController make(Property $property)
 */
class NativePropertyController
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
        return view(Template::native->value, ['Property' => $this->Property], __DIR__);
    }
}