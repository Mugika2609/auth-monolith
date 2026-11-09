<?php

namespace Modules\Shared\Exceptions;

use InvalidArgumentException;

class InvalidIsbnException extends InvalidArgumentException
{
    public static function for(string $value): self
    {
        return new self("ISBN tidak valid: {$value}");
    }
}