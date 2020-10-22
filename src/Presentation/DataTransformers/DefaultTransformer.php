<?php


namespace Yashry\Presentation\DataTransformers;


class DefaultTransformer implements IJsonTransformer
{
    private object $dto;

    public function __construct(object $dto)
    {
        $this->dto = $dto;
    }

    public function toJson(): string
    {
        $array = get_object_vars($this->dto);
        return json_encode($array);
    }
}