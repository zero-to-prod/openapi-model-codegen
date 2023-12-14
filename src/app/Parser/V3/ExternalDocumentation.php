<?php

namespace Zerotoprod\ModelCodegen\Parser\V3;

use Zerotoprod\ServiceModel\ServiceModel;

class ExternalDocumentation
{
    use ServiceModel;

    public readonly string $url;
    public readonly ?string $description;
}
