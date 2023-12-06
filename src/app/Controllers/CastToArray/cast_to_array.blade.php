@php
    use Zerotoprod\ModelCodegen\Models\Property;
    /* @var Property $Property */
@endphp
    /* @var {{$Property->ref_classname}}[] ${{$Property->name}} */
    #[CastToArray({{$Property->ref_classname}}::class)]
    {{$Property->visibility->value}} {{$Property->readonly ? 'readonly' : null}} {{implode('|', $Property->declarations)}} ${{$Property->name}};