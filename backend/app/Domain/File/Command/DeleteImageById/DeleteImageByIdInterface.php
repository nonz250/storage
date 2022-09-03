<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Domain\File\Command\DeleteImageById;

interface DeleteImageByIdInterface
{
    public function process(DeleteImageByIdInputPort $inputPort): void;
}
