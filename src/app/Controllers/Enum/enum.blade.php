@php
    use Zerotoprod\ModelCodegen\Models\Property;
    /* @var Property $Property */
@endphp
{{$Property->visibility->value}}{{isset($Property->readonly) && $Property->readonly ? ' readonly' : null}} {{implode('|', $Property->declarations)}} ${{$Property->name}};