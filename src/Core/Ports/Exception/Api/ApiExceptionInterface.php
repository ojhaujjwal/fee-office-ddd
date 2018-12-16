<?php

declare(strict_types=1);

namespace App\Core\Ports\Exception\Api;

use Zend\ProblemDetails\Exception\ProblemDetailsExceptionInterface;

interface ApiExceptionInterface extends ProblemDetailsExceptionInterface
{
    /**
     * An application-specific error code.
     *
     * @return int
     */
    public function getErrorCode(): int;

    /**
     * Non-standard meta-information about the error.
     *
     * @return array
     */
    public function getMeta(): array;
}
