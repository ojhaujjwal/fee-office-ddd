<?php

declare(strict_types=1);

namespace App\Core\Ports\Exception\Api;

use Throwable;

final class BadRequestException extends \DomainException implements ApiExceptionInterface
{
    use ApiExceptionTrait;

    private const TITLE = 'Bad Request';
    private const STATUS = 400;
    private const ERROR_CODE = 2;

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

    public static function withDetail(string $detail): self
    {
        $exception = new self($detail);
        $exception->title = self::TITLE;
        $exception->status = self::STATUS;
        $exception->detail = $detail;
        $exception->errorCode = self::ERROR_CODE;

        return $exception;
    }
}
