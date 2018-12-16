<?php

declare(strict_types=1);

namespace App\Core\Ports\Exception\Api;

use Throwable;

final class ResourceNotFoundException extends \DomainException implements ApiExceptionInterface
{
    use ApiExceptionTrait;

    private const TITLE = 'Resource Not Found';
    private const STATUS = 404;
    private const ERROR_CODE = 3;

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

    public static function withResource(string $resource): self
    {
        $exception = new static(sprintf('%s not found', $resource));
        $exception->title = self::TITLE;
        $exception->status = self::STATUS;
        $exception->detail = $exception->getMessage();
        $exception->errorCode = self::ERROR_CODE;

        return $exception;
    }
}
