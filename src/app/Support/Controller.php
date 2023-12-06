<?php /** @noinspection PhpParamsInspection */

namespace Zerotoprod\ModelCodegen\Support;

trait Controller
{
    public static function make(...$args): self
    {
        return (new self)->handle(...$args);
    }

    public function render(): string
    {
        return '';
    }
}