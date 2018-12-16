<?php

declare(strict_types=1);

namespace App\Core\Ports\Exception\Api;

use Throwable;

final class ValidationErrorException extends \DomainException implements ApiExceptionInterface
{
    use ApiExceptionTrait;

    private const TITLE = 'Validation Error';
    private const STATUS = 422;
    private const DETAIL = 'Some error occurred in the provided inputs.';
    private const ERROR_CODE = 1;

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

    public static function withErrors(array $errors): self
    {
        $exception = new self(self::DETAIL);
        $exception->meta = ['errors' => $errors];
        $exception->title = self::TITLE;
        $exception->status = self::STATUS;
        $exception->detail = self::DETAIL;
        $exception->errorCode = self::ERROR_CODE;

        return $exception;
    }
}
