<?php

declare(strict_types=1);

namespace App\Core\Ports\Exception\Api;

use Throwable;

final class InternalServerErrorException extends \DomainException implements ApiExceptionInterface
{
    use ApiExceptionTrait;

    private const TITLE = 'Unknown Error';
    private const STATUS = 500;
    private const ERROR_CODE = 0;

    /**
     * Make constructor private so that it is not instantiable directly.
     *
     * ValidationErrorException constructor.
     *
     * @param string         $message
     * @param int            $code
     * @param null|Throwable $previous
     */
    private function __construct(string $message = '', int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function withException(Throwable $previous): self
    {
        $detail = 'An Unknown error occurred. Please try again later.';

        $exception = new self($detail, 0, $previous);
        $exception->title = self::TITLE;
        $exception->status = self::STATUS;
        $exception->detail = $detail;
        $exception->errorCode = self::ERROR_CODE;

        return $exception;
    }
}
