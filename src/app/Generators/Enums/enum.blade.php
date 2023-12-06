@php
    use Zerotoprod\ModelCodegen\Generators\Enums\EnumModel;
    /* @var EnumModel $EnumModel */
echo '<?php'
@endphp


@isset($EnumModel->namespace)
namespace {{$EnumModel->namespace}};
@endif

enum {{$EnumModel->classname}}: string
{
@foreach($EnumModel->values as $value)
    case {{to_valid_identifier($value)}} = '{{$value}}';
@endforeach
}