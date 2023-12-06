<?php

namespace Zerotoprod\ModelCodegen\Generators\Enums;

use Zerotoprod\ModelCodegen\Support\Controller;

/**
 * @method static EnumController make(?string $namespace, string $classname, array $values)
 */
class EnumController
{
    use Controller;

    public readonly EnumModel $EnumModel;

    public function handle(?string $namespace, string $classname, array $values): self
    {
        $this->EnumModel = EnumModel::make([
            EnumModel::namespace => $namespace,
            EnumModel::classname => $classname,
            EnumModel::values => $values,
        ]);

        return $this;
    }

    /**
     * @return string
     */
    public function render(): string
    {
        return view('enum', ['EnumModel' => $this->EnumModel], __DIR__);
    }
}