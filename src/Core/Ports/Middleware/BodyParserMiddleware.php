<?php

declare(strict_types=1);

namespace App\Core\Ports\Middleware;

use App\Core\Ports\Exception\Api\BadRequestException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use const JSON_ERROR_NONE;
use function json_decode;
use function json_last_error;
use function json_last_error_msg;

final class BodyParserMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (\in_array($request->getMethod(), ['POST', 'PATCH', 'PUT', 'DELETE'], true)) {
            $contentType = $request->getHeaderLine('Content-type');
            if ('application/x-www-form-urlencoded' === strtolower($contentType)
                && null === $request->getParsedBody()
            ) {
                $parsedBody = [];
                mb_parse_str((string) $request->getBody(), $parsedBody);
                $request = $request->withParsedBody($parsedBody);
            } elseif (false !== mb_strpos($contentType, '/json') ||
                false !== mb_strpos($contentType, '+json')
            ) {
                $parsedBody = json_decode((string) $request->getBody(), true);
                $code = json_last_error();

                if (JSON_ERROR_NONE !== $code) {
                    throw BadRequestException::withDetail(sprintf('JSON Parse Error: %s', json_last_error_msg()));
                }
                $request = $request->withParsedBody($parsedBody);
            }
        }

        return $handler->handle($request);
    }
}
