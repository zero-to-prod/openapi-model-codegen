<?php

namespace Zerotoprod\ModelCodegen\Parser\V3;

use RuntimeException;
use Zerotoprod\ServiceModel\ServiceModel;

class Reference
{
    use ServiceModel;

    public const _ref = '_ref';
    public readonly string $_ref;

    public function __isset($name)
    {
        return isset($this->$name);
    }

    /**
     * @throws RuntimeException
     */
    public function __set($name, $value)
    {
        if (property_exists($this, $name)) {
            $this->$name = $value;
        } else {
            throw new RuntimeException("Invalid property: $name");
        }
    }

    /**
     * @throws RuntimeException
     */
    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        }

        throw new RuntimeException("Invalid property: $name");
    }
}
