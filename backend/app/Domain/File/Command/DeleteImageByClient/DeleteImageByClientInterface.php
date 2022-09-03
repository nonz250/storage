<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Domain\File\Command\DeleteImageByClient;

interface DeleteImageByClientInterface
{
    /**
     * @param DeleteImageByClientInputPort $inputPort
     *
     * @throws DeleteImageException
     */
    public function process(DeleteImageByClientInputPort $inputPort): void;
}
