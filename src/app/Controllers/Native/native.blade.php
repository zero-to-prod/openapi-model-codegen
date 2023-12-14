@php
    use Zerotoprod\ModelCodegen\Models\Property;
    /* @var Property $Property */
@endphp
@isset($Property->comment)
    /**
     * {{$Property->comment}}
     */
@endisset
    {{$Property->value}};