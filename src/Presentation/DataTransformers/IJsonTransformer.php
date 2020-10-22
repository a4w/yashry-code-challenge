<?php


namespace Yashry\Presentation\DataTransformers;


interface IJsonTransformer
{
    public function toJson(): String;
}