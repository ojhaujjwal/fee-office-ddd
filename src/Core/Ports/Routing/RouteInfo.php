<?php

declare(strict_types=1);

namespace App\Core\Ports\Routing;

class RouteInfo
{
    private const NOT_FOUND = 0;
    private const FOUND = 1;
    private const METHOD_NOT_ALLOWED = 2;

    /**
     * @var int
     */
    private $status;

    /**
     * @var array
     */
    private $handlers;

    /**
     * @var array
     */
    private $params;

    public static function fromSuccess(array $handlers, array $params): self
    {
        $self = new self();

        $self->status = self::FOUND;
        $self->handlers = $handlers;
        $self->params = $params;

        return $self;
    }

    public static function fromNotFound(): self
    {
        $self = new self();
        $self->status = self::NOT_FOUND;

        return $self;
    }

    public static function fromMethodNotAllowed(): self
    {
        $self = new self();
        $self->status = self::METHOD_NOT_ALLOWED;

        return $self;
    }

    public function getHandlers(): ?array
    {
        if (!$this->isSuccess()) {
            return null;
        }

        return $this->handlers;
    }

    public function getParams(): ?array
    {
        if (!$this->isSuccess()) {
            return null;
        }

        return $this->params;
    }

    public function isSuccess(): bool
    {
        return self::FOUND === $this->status;
    }

    public function isNotFound(): bool
    {
        return self::NOT_FOUND === $this->status;
    }

    public function isMethodNotAllowed(): bool
    {
        return self::METHOD_NOT_ALLOWED === $this->status;
    }
}
