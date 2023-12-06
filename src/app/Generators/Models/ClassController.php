<?php

namespace Zerotoprod\ModelCodegen\Generators\Models;

use Zerotoprod\ModelCodegen\Enums\Template;
use Zerotoprod\ModelCodegen\Enums\Visibility;
use Zerotoprod\ModelCodegen\Models\EnumDto;
use Zerotoprod\ModelCodegen\Models\Property;
use Zerotoprod\ModelCodegen\Rules\CastsToArray;
use Zerotoprod\ModelCodegen\Support\Controller;
use Zerotoprod\ServiceModel\CastToArray;
use Zerotoprod\ServiceModel\ServiceModel;

/**
 * @method static self make(string $classname, array $properties, string $namespace)
 */
class ClassController
{
    use Controller;

    public readonly ClassModel $ClassModel;

    /**
     * @param string $classname
     * @param array $properties
     * @param ?string $namespace
     * @return $this
     */
    public function handle(string $classname, array $properties, ?string $namespace): self
    {
        $traits = [ServiceModel::class];
        $imports = [];
        if (CastsToArray::evaluate($properties)) {
            $imports[] = CastToArray::class;
        }

        $class_properties = [];
        foreach ($properties as $property_name => $property_value) {
            $nullable = !empty($property_value['nullable']) && $property_value['nullable'] === true;
            if (isset($property_value['type'], $property_value['items']['$ref']) && $property_value['type'] === 'array') {
                $class_properties[] = Property::make([
                    Property::visibility => Visibility::public->value,
                    Property::readonly => true,
                    Property::declarations => $nullable ? ['?array'] : ['array'],
                    Property::ref_classname => $this->extractClassName($property_value['items']['$ref']),
                    Property::name => $property_name,
                    Property::template => Template::cast_to_array,
                ]);
            } else {
                $enum_class_name = $this->extractClassName($property_value['$ref'] ?? null);
                if ($enum_class_name) {
                    $class_properties[] = Property::make([
                        Property::visibility => Visibility::public->value,
                        Property::readonly => true,
                        Property::declarations => $nullable ? ['?' . $enum_class_name] : [$enum_class_name],
                        Property::ref_classname => null,
                        Property::name => $property_name,
                        Property::template => Template::enum,
                    ]);
                } else {
                    $native_type = match ($property_value['type'] ?? null) {
                        'string' => 'string',
                        'integer' => 'int',
                        'number' => 'float',
                        'boolean' => 'bool',
                        default => 'mixed',
                    };
                    $declaration = $nullable ? ['?' . $native_type] : [$native_type];
                    $class_properties[] = Property::make([
                        Property::visibility => Visibility::public->value,
                        Property::readonly => true,
                        Property::declarations => $declaration === ['?mixed'] ? ['mixed'] : $declaration,
                        Property::ref_classname => null,
                        Property::name => $property_name,
                        Property::template => Template::native
                    ]);
                }
            }
        }

        $this->ClassModel = new ClassModel([
            ClassModel::classname => $classname,
            ClassModel::namespace => $namespace,
            ClassModel::imports => $imports,
            ClassModel::traits => $traits,
            ClassModel::properties => $class_properties,
        ]);

        return $this;
    }

    public function render(): string
    {
        return view('class', ['ClassModel' => $this->ClassModel], __DIR__);
    }

    private function extractClassName(?string $ref)
    {
        if (!$ref) {
            return null;
        }

        $parts = explode('/', $ref);

        return end($parts);
    }
}