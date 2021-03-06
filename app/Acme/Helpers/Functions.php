<?php

use Illuminate\Validation\ValidationException;

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

/**
 * @param $errors
 * @throws ValidationException
 */
function setErrorAfterValidation($errors)
{
    $validator = Validator::make([], []);
    foreach($errors as $key => $error)
    {
        $validator->errors()->add($key, $error);
    }

    throw new ValidationException($validator);
}

function getW3cDatetime($datetime)
{
    return $datetime ? $datetime->toW3cString() : null;
}