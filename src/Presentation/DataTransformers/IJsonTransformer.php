<?php


namespace Yashry\Presentation\DataTransformers;


/**
 * Contract saying that the implementors must put a JSON transform method
 * @package Yashry\Presentation\DataTransformers
 */
interface IJsonTransformer
{
    public function toJson(): string;
}