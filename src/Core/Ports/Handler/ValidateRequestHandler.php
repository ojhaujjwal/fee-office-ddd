<?php

declare(strict_types=1);

namespace App\Core\Ports\Handler;

use App\Core\Ports\Command\ValidateRequest;
use App\Core\Ports\Exception\Api\ValidationErrorException;

final class ValidateRequestHandler
{
    /**
     * @param ValidateRequest $command
     *
     * @throws ValidationErrorException
     *
     * @return bool
     */
    public function handle(ValidateRequest $command): bool
    {
        $inputFilter = $command->getInputFilter();

        $inputFilter->setData($command->getRequest()->getParsedBody());
        if (!$inputFilter->isValid()) {
            throw ValidationErrorException::withErrors($inputFilter->getMessages());
        }

        return true;
    }
}
