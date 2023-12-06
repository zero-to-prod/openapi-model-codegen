<?php

use Zerotoprod\ModelCodegen\Controllers\CastToArray\CastToArrayController;
use Zerotoprod\ModelCodegen\Enums\Declaration;
use Zerotoprod\ModelCodegen\Enums\Visibility;
use Zerotoprod\ModelCodegen\Models\Property;

test('casts to array', function () {
    $view = CastToArrayController::make(
        Property::make([
            Property::visibility => Visibility::public->value,
            Property::readonly => true,
            Property::declarations => [Declaration::string->value, Declaration::null->value],
            Property::ref_classname => 'Order',
            Property::name => 'name',
        ])
    )->render();

    expect($view)->toContain('/* @var Order[] $name */')
        ->and($view)->toContain('#[CastToArray(Order::class)]')
        ->and($view)->toContain('public readonly string|null $name;');
});
