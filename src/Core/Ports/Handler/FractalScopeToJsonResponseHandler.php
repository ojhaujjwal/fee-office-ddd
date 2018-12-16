<?php

declare(strict_types=1);

namespace App\Core\Ports\Handler;

use App\Core\Ports\Command\FractalScopeToJsonResponse;
use League\Fractal\Serializer\JsonApiSerializer;
use Zend\Diactoros\Response\JsonResponse;

final class FractalScopeToJsonResponseHandler
{
    public const JSON_API_CONTENT_TYPE = 'application/vnd.api+json';

    public function handle(FractalScopeToJsonResponse $command): JsonResponse
    {
        $scope = $command->getScope();
        $headers = $command->getHeaders();

        if ($scope->getManager()->getSerializer() instanceof JsonApiSerializer) {
            $headers['content-type'] = self::JSON_API_CONTENT_TYPE;
        }

        return new JsonResponse($scope->toArray(), $command->getStatus(), $headers);
    }
}
