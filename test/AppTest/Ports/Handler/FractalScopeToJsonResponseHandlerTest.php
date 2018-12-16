<?php

declare(strict_types=1);

namespace AppTest\Ports\Handler;

use App\Core\Ports\Command\FractalScopeToJsonResponse;
use App\Core\Ports\Handler\FractalScopeToJsonResponseHandler;
use League\Fractal\Manager;
use League\Fractal\Scope;
use League\Fractal\Serializer\DataArraySerializer;
use League\Fractal\Serializer\JsonApiSerializer;
use Mockery;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Core\Ports\Handler\FractalScopeToJsonResponseHandler
 */
class FractalScopeToJsonResponseHandlerTest extends TestCase
{
    public function setContentTypeData()
    {
        return [
            [Mockery::mock(JsonApiSerializer::class), FractalScopeToJsonResponseHandler::JSON_API_CONTENT_TYPE],
            [DataArraySerializer::class, 'application/json'],
        ];
    }

    /**
     * @dataProvider setContentTypeData
     *
     * @param $serializer
     * @param $expectedContentType
     */
    public function testSetContentType($serializer, $expectedContentType): void
    {
        $scope = Mockery::mock(Scope::class);
        $command = Mockery::mock(FractalScopeToJsonResponse::class);
        $manager = Mockery::mock(Manager::class);

        $headers = ['Server' => 'MyServer', 'content-type' => 'application/json'];

        $command->shouldReceive('getScope')
            ->once()
            ->withNoArgs()
            ->andReturn($scope);

        $scope->shouldReceive('toArray')
            ->once()
            ->withNoArgs()
            ->andReturn(['data' => 'some data']);

        $scope->shouldReceive('getManager')
            ->once()
            ->withNoArgs()
            ->andReturn($manager);

        $manager->shouldReceive('getSerializer')
            ->once()
            ->withNoArgs()
            ->andReturn($serializer);

        $command->shouldReceive('getHeaders')
            ->once()
            ->withNoArgs()
            ->andReturn($headers);

        $command->shouldReceive('getStatus')
            ->once()
            ->withNoArgs()
            ->andReturn(202);

        $response = (new FractalScopeToJsonResponseHandler())->handle($command);

        $this->assertSame(202, $response->getStatusCode());
        $this->assertSame('MyServer', $response->getHeaderLine('Server'));
        $this->assertSame($expectedContentType, $response->getHeaderLine('Content-Type'));
    }

    public function tearDown()
    {
        Mockery::close();
        parent::tearDown();
    }
}
