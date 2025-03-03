<?php


namespace App\Utilities\Enum;

use ReflectionClass;
use Illuminate\Support\Facades\Log;

abstract class BasicEnum
{
    /**
     * @return integer
     */
    public static function getKey($value)
    {
        try {
            $class = new ReflectionClass(get_called_class());
            $enum = array_flip($class->getConstants());

            return $enum[$value];
        } catch (\ReflectionException $e) {
            Log::error('Reflection Class Error: ' . $e->getMessage());
        }
        return null;
    }

    public static function getValues()
    {
        try {
            $class = new ReflectionClass(get_called_class());
        } catch (\ReflectionException $e) {
            Log::error('Reflection Class Error: ' . $e->getMessage());
        }

        return array_values($class->getConstants());
    }

    public static function getValue($value)
    {
        try {
            $class = new ReflectionClass(get_called_class());
            $enum = $class->getConstants();

            return $enum[$value];
        } catch (\ReflectionException $e) {
            Log::error('Reflection Class Error: ' . $e->getMessage());
        }
        return null;
    }

}
