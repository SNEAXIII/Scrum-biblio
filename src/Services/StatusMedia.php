<?php

namespace App\Services;

use ReflectionClass;

class StatusMedia
{
    public const NON_DISPONIBLE = "NON_DISPONIBLE";
    public const NOUVEAU = "NOUVEAU";
    public const DISPONIBLE = "DISPONIBLE";
    public const EMPRUNTE = "EMPRUNTE";

    public static function getStatusName($value): ?string
    {
        $reflectionClass = new ReflectionClass(__CLASS__);
        $constants = $reflectionClass->getConstants();
        foreach ($constants as $name => $constantValue) {
            // TODO dangereux je ne fais pas la comparaison stricte
            if ($constantValue == $value) {
                return ucfirst(strtolower($name));
            }
        }
        return null;
    }
}