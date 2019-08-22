<?php

/**
 * @param $image
 * @param $default
 * @return mixed
 */
function getImageLink($image, $default)
{
    if(strpos($image, 'http://')===false && strpos($image, 'https://')===false)
        return $image ? Storage::disk('public')->url(str_replace('public/storage', '', $image)) : $default;

    return $image;
}

function sumAmounts($a, $b, $scale=0)
{
    return bcadd($a, $b, $scale);
}
