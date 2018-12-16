<?php

declare(strict_types=1);

namespace App\Core\Ports\Factory;

use League\Fractal\Manager;
use League\Fractal\Serializer\DataArraySerializer;
use League\Fractal\Serializer\JsonApiSerializer;
use Negotiation\Accept;
use Negotiation\Negotiator;
use Psr\Http\Message\ServerRequestInterface;

class FractalManagerFactory
{
    /**
     * @var Negotiator
     */
    private $negotiator;

    /**
     * FractalManagerFactory constructor.
     *
     * @param Negotiator $negotiator
     */
    public function __construct(Negotiator $negotiator)
    {
        $this->negotiator = $negotiator;
    }

    public function fromAcceptHeader(string $header)
    {
        $fractal = new Manager();

        /** @var Accept $mediaType */
        $mediaType = $this->negotiator->getBest(
            $header,
            ['application/vnd.api+json', 'application/json']
        );

        if ('application/vnd.api+json' === $mediaType->getValue()) {
            $fractal->setSerializer(new JsonApiSerializer());
        } else {
            $fractal->setSerializer(new DataArraySerializer());
        }

        return $fractal;
    }

    public function fromRequest(ServerRequestInterface $request)
    {
        return $this->fromAcceptHeader($request->getHeaderLine('Accept'));
    }
}
