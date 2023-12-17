<?php

namespace App\Services;

use ReflectionClass;

class StatusMedia
{
    public const NON_DISPONIBLE = 0;
    public const NOUVEAU = 1;
    public const VERIFICATION = 2;
    public const EMPRUNTE = 3;

    public static function getStatusName($value): ?string
    {
        $reflectionClass = new ReflectionClass(__CLASS__);
        $constants = $reflectionClass->getConstants();
        var_dump($constants);
        // TODO dangereux je ne fais pas la comparaison stricte
        foreach ($constants as $name => $constantValue) {
            if ($constantValue == $value) {
                return ucfirst(strtolower($name));
            }
        }

        return null;
    }
}