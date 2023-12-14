<?php

use Zerotoprod\ModelCodegen\Controllers\CastToArray\CastToArrayController;
use Zerotoprod\ModelCodegen\Models\Property;

test('casts to array', function () {
    $view = CastToArrayController::make(
        Property::make([
            Property::doc_block_value => '@var Order[] $name',
            Property::ref_classname => 'Order',
            Property::value => 'public readonly string|null $name',
        ])
    )->render();

    expect($view)->toContain('@var Order[] $name')
        ->and($view)->toContain('#[CastToArray(Order::class)]')
        ->and($view)->toContain('public readonly string|null $name;');
});
test('comment', function () {
    $view = CastToArrayController::make(
        Property::make([
            Property::doc_block_value => '@var Order[] $name',
            Property::ref_classname => 'Order',
            Property::comment => 'comment',
            Property::value => 'public readonly string|null $name',
        ])
    )->render();

    expect($view)->toContain('comment');
});