<?php

declare(strict_types=1);

namespace App\Core\Ports\Command;

use League\Fractal\Scope;

class FractalScopeToJsonResponse
{
    /**
     * @var Scope
     */
    private $scope;

    /**
     * @var int
     */
    private $status;

    /**
     * @var array
     */
    private $headers;

    /**
     * FractalScopeToJsonResponse constructor.
     *
     * @param Scope $scope
     * @param int   $status
     * @param array $headers
     */
    public function __construct(Scope $scope, int $status = 200, array $headers = [])
    {
        $this->scope = $scope;
        $this->status = $status;
        $this->headers = $headers;
    }

    /**
     * @return Scope
     */
    public function getScope(): Scope
    {
        return $this->scope;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }
}
