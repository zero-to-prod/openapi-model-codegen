@php
    use Zerotoprod\ModelCodegen\Controllers\CastToArray\CastToArrayController;
    use Zerotoprod\ModelCodegen\Controllers\Enum\EnumPropertyController;
    use Zerotoprod\ModelCodegen\Controllers\Native\NativePropertyController;
    use Zerotoprod\ModelCodegen\Generators\Models\ClassModel;
    use Zerotoprod\ModelCodegen\Enums\Template;
    /* @var ClassModel $ClassModel */
echo '<?php' . PHP_EOL;
@endphp

@isset($ClassModel->namespace)namespace {{$ClassModel->namespace}};{!! PHP_EOL !!}
@endif
@foreach($ClassModel->imports as $import)use {!! $import !!};
@endforeach

class {{$ClassModel->classname}}
{

@foreach($ClassModel->traits as $trait)
    use \{!! $trait !!};
@endforeach

@foreach($ClassModel->properties as $property)
    @switch($property->template)
        @case(Template::cast_to_array){!! CastToArrayController::make($property)->render() . PHP_EOL!!}@break
        @case(Template::enum){!! EnumPropertyController::make($property)->render() . PHP_EOL !!}@break
        @case(Template::native){!! NativePropertyController::make($property)->render() . PHP_EOL !!}@break
        @default
    @endswitch
@endforeach

}