<?php

namespace App\Services;

class MediaNormalizeArray
{
    function execute(array $arrayMedia)
    {
        $normalizedMedia = [];
        foreach ($arrayMedia as $media) {
            $normalizedMedia[] = new NormaliseMedia($media);
        }
        return $normalizedMedia;
    }
}