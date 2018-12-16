<?php

declare(strict_types=1);

namespace AppTest\Core\Ports\Factory;

use App\Core\Ports\Factory\FractalManagerFactory;
use League\Fractal\Manager;
use League\Fractal\Serializer\DataArraySerializer;
use League\Fractal\Serializer\JsonApiSerializer;
use Mockery;
use Negotiation\Accept;
use Negotiation\Negotiator;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @covers \App\Core\Ports\Factory\FractalManagerFactory
 */
class FractalManagerFactoryTest extends TestCase
{
    /** @var Mockery\MockInterface */
    private $negotiator;
    /** @var FractalManagerFactory */
    private $fractalManagerFactory;

    public function setUp()
    {
        parent::setUp();
        $this->negotiator = Mockery::mock(Negotiator::class);
        $this->fractalManagerFactory = new FractalManagerFactory($this->negotiator);
    }

    public function testCreateJsonApiSerializerBasedFractalManager()
    {
        $request = Mockery::mock(ServerRequestInterface::class);

        $request->shouldReceive('getHeaderLine')
            ->once()
            ->with('Accept')
            ->once()
            ->andReturn('application/vnd.api+json');

        $this->negotiator->shouldReceive('getBest')
            ->once()
            ->with('application/vnd.api+json', Mockery::type('array'))
            ->andReturn(new Accept('application/vnd.api+json'));

        $manager = $this->fractalManagerFactory->fromRequest($request);
        $this->assertInstanceOf(Manager::class, $manager);
        $this->assertInstanceOf(JsonApiSerializer::class, $manager->getSerializer());
    }

    public function testDataArraySerializerBasedFractalManager()
    {
        $request = Mockery::mock(ServerRequestInterface::class);

        $request->shouldReceive('getHeaderLine')
            ->once()
            ->with('Accept')
            ->once()
            ->andReturn('application/json');

        $this->negotiator->shouldReceive('getBest')
            ->once()
            ->with('application/json', Mockery::type('array'))
            ->andReturn(new Accept('application/json'));

        $manager = $this->fractalManagerFactory->fromRequest($request);
        $this->assertInstanceOf(Manager::class, $manager);
        $this->assertInstanceOf(DataArraySerializer::class, $manager->getSerializer());
    }
}
