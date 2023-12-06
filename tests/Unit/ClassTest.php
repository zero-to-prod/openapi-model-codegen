<?php

use Zerotoprod\ModelCodegen\Enums\Declaration;
use Zerotoprod\ModelCodegen\Enums\Template;
use Zerotoprod\ModelCodegen\Enums\Visibility;
use Zerotoprod\ModelCodegen\Generators\Models\ClassController;
use Zerotoprod\ServiceModel\CastToArray;
use Zerotoprod\ServiceModel\ServiceModel;

test('default trait', function () {
    $ClassController = ClassController::make(
        classname: 'Order',
        properties: [],
        namespace: 'Zerotoprod\ModelCodegen\Models'
    );

    $ClassModel = $ClassController->ClassModel;

    expect($ClassModel->traits)->toBe([ServiceModel::class])
        ->and($ClassModel->classname)->toBe('Order');
});

test('casts to array', function () {
    $ClassController = ClassController::make(
        classname: 'Order',
        properties: [
            'Order' => [
                'type' => 'array',
                'nullable' => true,
                'items' => [
                    '$ref' => '#/components/schemas/OrderItem',
                ],
            ]],
        namespace: 'Zerotoprod\ModelCodegen\Models'
    );

    validate_view($ClassController->render());

    $ClassModel = $ClassController->ClassModel;
    expect($ClassModel->traits)->toBe([ServiceModel::class])
        ->and($ClassModel->imports)->toBe([CastToArray::class]);

    $Property = $ClassController->ClassModel->properties[0];
    expect($Property->visibility)->toBe(Visibility::public)
        ->and($Property->readonly)->toBe(true)
        ->and($Property->declarations)->toBe(['?' . Declaration::array->value])
        ->and($Property->name)->toBe('Order')
        ->and($Property->ref_classname)->toBe('OrderItem')
        ->and($Property->template)->toBe(Template::cast_to_array);
});

test('enum', function () {
    $ClassController = ClassController::make(
        classname: 'Order',
        properties: [
            'orderType' => [
                '$ref' => '#/components/schemas/OrderType',
            ]
        ],
        namespace: 'App\Models'
    );
    validate_view($ClassController->render());

    $ClassModel = $ClassController->ClassModel;
    expect($ClassModel->traits)->toBe([ServiceModel::class]);

    $Property = $ClassController->ClassModel->properties[0];
    expect($Property->visibility)->toBe(Visibility::public)
        ->and($Property->readonly)->toBe(true)
        ->and($Property->declarations)->toBe(['OrderType'])
        ->and($Property->name)->toBe('orderType')
        ->and($Property->template)->toBe(Template::enum);
});

test('native type', function () {
    $ClassController = ClassController::make(
        classname: 'Order',
        properties: [
            'amount' => [
                'type' => 'number',
                'format' => 'double'
            ]
        ],
        namespace: 'App\Models'
    );

    validate_view($ClassController->render());

    $ClassModel = $ClassController->ClassModel;
    expect($ClassModel->traits)->toBe([ServiceModel::class])
        ->and($ClassModel->imports)->toBe([]);

    $Property = $ClassController->ClassModel->properties[0];
    expect($Property->visibility)->toBe(Visibility::public)
        ->and($Property->readonly)->toBe(true)
        ->and($Property->declarations)->toBe(['float'])
        ->and($Property->name)->toBe('amount')
        ->and($Property->template)->toBe(Template::native);
});

test('prevent optional mixed type', function () {
    $ClassController = ClassController::make(
        classname: 'Order',
        properties: [
            'amount' => [
                'nullable' => true,
                'type' => 'unknown',
                'format' => 'unknown'
            ]
        ],
        namespace: 'App\Models'
    );

    $Property = $ClassController->ClassModel->properties[0];
    expect($Property->declarations)->toBe(['mixed']);
});

