<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Domain\File\Command\DeleteImageByClient;

interface DeleteImageByClientInterface
{
    public function process(DeleteImageByClientInputPort $inputPort): void;
}
