<?php

namespace Zerotoprod\ModelCodegen\Generators\Models;

use Exception;
use Zerotoprod\ModelCodegen\Enums\Template;
use Zerotoprod\ModelCodegen\Enums\Visibility;
use Zerotoprod\ModelCodegen\Models\Property;
use Zerotoprod\ModelCodegen\Parser\DataTypes;
use Zerotoprod\ModelCodegen\Parser\V3\Schema;
use Zerotoprod\ModelCodegen\Rules\CastsToArray;
use Zerotoprod\ModelCodegen\Support\Controller;
use Zerotoprod\ServiceModel\CastToArray;
use Zerotoprod\ServiceModel\ServiceModel;

/**
 * @method static self make(array $schema, string $classname, array $properties, string $namespace)
 */
class ClassController
{
    use Controller;

    public readonly ?ClassModel $ClassModel;

    /**
     * @param Schema[] $schema
     * @throws Exception
     */
    public function handle(array $schema, string $classname, array $properties, ?string $namespace): self
    {
        if (empty($properties)) {
            $this->ClassModel = null;

            return $this;
        }

        $traits = [ServiceModel::class];
        $imports = [];
        if (CastsToArray::evaluate($properties)) {
            $imports[] = CastToArray::class;
        }

        $class_properties = [];

        /** @var Schema $property_value */
        foreach ($properties as $property_name => $property_value) {
            if (isset($property_value->_ref)) {
                if (isset($schema[$classname]['required'])
                    && in_array($property_name, $schema[$classname]['required'], true)
                ) {
                    $default_value = $property_value->minimum ?? 'null';
                    $optional = isset($property_value->minimum) ? null : '?';
                    $class_properties[] = Property::make([
                        Property::value => "public $optional$property_value->_ref \$$property_name = $default_value",
                        Property::template => Template::native,
                    ]);
                    continue;
                }
                $nullable = $property_value->nullable ?? false ? '?' : null;
                $class_properties[] = Property::make([
                    Property::value => "public readonly $nullable$property_value->_ref \$$property_name",
                    Property::template => Template::native,
                ]);
                continue;
            }
            if (isset($property_value->type, $property_value->items['$ref']->_ref) && $property_value->type === 'array') {
                if (isset($schema[$classname]['required'])
                    && in_array($property_name, $schema[$classname]['required'], true)
                ) {
                    $nullable = $property_value->nullable ?? false ? '?' : null;
                    $ref_classname = $property_value->items['$ref']->_ref;
                    $class_properties[] = Property::make([
                        Property::doc_block_value => "{$ref_classname}[]|null \$$property_name",
                        Property::ref_classname => $ref_classname,
                        Property::name => $property_name,
                        Property::value => "public {$nullable}array \$$property_name = []",
                        Property::comment => $property_value->description ?? null,
                        Property::template => Template::cast_to_array,
                    ]);
                    continue;
                }

                $nullable = $property_value->nullable ?? false ? '?' : null;
                $ref_classname = $property_value->items['$ref']->_ref;
                $class_properties[] = Property::make([
                    Property::doc_block_value => "{$ref_classname}[] \$$property_name",
                    Property::ref_classname => $ref_classname,
                    Property::name => $property_name,
                    Property::value => "public readonly {$nullable}array \$$property_name",
                    Property::comment => $property_value->description ?? null,
                    Property::template => Template::cast_to_array,
                ]);

                continue;
            }
            if (isset($property_value->enum)) {
                $enum_classname = ucfirst(to_valid_identifier($property_name));
                $class_properties[] = Property::make([
                    Property::visibility => Visibility::public->value,
                    Property::readonly => true,
                    Property::declarations => $property_value->nullable ?? false ? ['?' . $enum_classname] : [$enum_classname],
                    Property::ref_classname => null,
                    Property::name => $property_name,
                    Property::comment => $property_value->description ?? null,
                    Property::template => Template::enum,
                ]);

                continue;
            }

            if (isset($property_value->items->enum)) {
                $enum_classname = ucfirst(to_valid_identifier($property_name));
                $class_properties[] = Property::make([
                    Property::visibility => Visibility::public->value,
                    Property::readonly => true,
                    Property::declarations => $property_value->nullable ?? false ? ['?' . $enum_classname] : [$enum_classname],
                    Property::ref_classname => null,
                    Property::name => $property_name,
                    Property::comment => $property_value->description ?? null,
                    Property::template => Template::enum,
                ]);

                continue;
            }
            $native_type = isset($property_value->type) ? DataTypes::get($property_value->type) : 'mixed';

            if (isset($schema[$classname]['required'])
                && in_array($property_name, $schema[$classname]['required'], true)
            ) {
                $default_value = $property_value->minimum ?? 'null';
                $optional = isset($property_value->minimum) ? null : '?';
                $class_properties[] = Property::make([
                    Property::value => "public $optional$native_type \$$property_name = $default_value",
                    Property::template => Template::native,
                ]);

                continue;
            }
            $nullable = $property_value->nullable ?? false ? '?' : null;
            $class_properties[] = Property::make([
                Property::value => "public readonly $nullable$native_type \$$property_name",
                Property::template => Template::native,
            ]);
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

    public function render(): ?string
    {
        if (!$this->ClassModel) {
            return null;
        }
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