<?php

use Zerotoprod\ModelCodegen\Generators\Enums\EnumController;

test('enum', function () {
    validate_view(EnumController::make(
        'App',
        'Types',
        ['value1', 'value2']
    )->render());
});

test('without namespace', function () {
    validate_view(EnumController::make(
        null,
        'Types',
        ['value1', 'value2']
    )->render());
});