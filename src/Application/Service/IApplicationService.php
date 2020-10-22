<?php


namespace Yashry\Application\Service;


use Yashry\Application\DataTransferObject\IDataTransferObject;

interface IApplicationService
{
    public function execute(IDataTransferObject $request): IDataTransferObject;
}