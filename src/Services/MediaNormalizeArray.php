<?php

namespace App\Services;

class MediaNormalizeArray
{
    function execute(array $arrayMedia): array
    {
        $arrayNormalizedMedia = [];
        foreach ($arrayMedia as $media) {
            $arrayNormalizedMedia[] = new NormaliseMedia($media);
        }
        return $arrayNormalizedMedia;
    }
}