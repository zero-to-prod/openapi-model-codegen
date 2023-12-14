@php
    use Zerotoprod\ModelCodegen\Models\Property;
    /* @var Property $Property */
@endphp
    /**
@isset($Property->comment)
     * {{$Property->comment}}
     *
@endisset
     * @var {{$Property->doc_block_value}}
     */
    #[CastToArray({{$Property->ref_classname}}::class)]
    {{$Property->value}};