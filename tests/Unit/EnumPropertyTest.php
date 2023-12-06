<?php

use Zerotoprod\ModelCodegen\Controllers\Enum\EnumPropertyController;
use Zerotoprod\ModelCodegen\Enums\Visibility;
use Zerotoprod\ModelCodegen\Models\Property;

test('enum property', function () {
    $view = EnumPropertyController::make(
        Property::make([
            Property::visibility => Visibility::public->value,
            Property::readonly => true,
            Property::declarations => ['Order'],
            Property::name => 'order',
        ])
    )->render();

    expect($view)->toContain('public readonly Order $order;');
});

test('not readonly', function () {
    $view = EnumPropertyController::make(
        Property::make([
            Property::visibility => Visibility::public->value,
            Property::declarations => ['Order'],
            Property::name => 'order',
        ])
    )->render();

    expect($view)->toContain('public Order $order;');
});