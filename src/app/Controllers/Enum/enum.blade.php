@php
    use Zerotoprod\ModelCodegen\Models\Property;
    /* @var Property $Property */
@endphp
@isset($Property->comment)
    /**
    * {{$Property->comment}}
    */
@endisset
    {{$Property->visibility->value}}{{isset($Property->readonly) && $Property->readonly ? ' readonly' : null}} {{implode('|', $Property->declarations)}} ${{$Property->name}};