<?php


namespace App\Models\Enum;

use ReflectionClass;

abstract class Enum
{
    /**
     * @return array
     * @throws \ReflectionException
     */
    public static function getValue(): array
    {
        $class = new ReflectionClass(get_called_class());
        return $class->getConstants();
    }
}
