<?php

declare(strict_types=1);

namespace App\Core\Ports\Command;

use Psr\Http\Message\ServerRequestInterface;
use Zend\InputFilter\InputFilter;

class ValidateRequest
{
    /**
     * @var ServerRequestInterface
     */
    private $request;

    /**
     * @var InputFilter
     */
    private $inputFilter;

    /**
     * ValidateRequest constructor.
     *
     * @param ServerRequestInterface $request
     * @param InputFilter            $inputFilter
     */
    public function __construct(ServerRequestInterface $request, InputFilter $inputFilter)
    {
        $this->request = $request;
        $this->inputFilter = $inputFilter;
    }

    /**
     * @return ServerRequestInterface
     */
    public function getRequest(): ServerRequestInterface
    {
        return $this->request;
    }

    /**
     * @return InputFilter
     */
    public function getInputFilter(): InputFilter
    {
        return $this->inputFilter;
    }
}
