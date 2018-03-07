<?php

/*
 * (c) Brieuc Thomas <tbrieuc@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ergast\HttpClient\Plugin;

use Http\Client\Common\Plugin;
use Psr\Http\Message\RequestInterface;

/**
 * Add response format attribute.
 *
 * @author Brieuc Thomas <tbrieuc@gmail.com>
 */
class ResponseFormat implements Plugin
{
    private $format;

    public function __construct(string $format)
    {
        $this->format = $format;
    }

    public function handleRequest(RequestInterface $request, callable $next, callable $first)
    {
        $path = $request->getUri()->getPath();

        if (false !== $cursor = mb_strrpos($path, '.')) {
            $path = mb_substr($path, 0, $cursor);
        }

        if ($this->format) {
            $path .= '.'.$this->format;
        }

        $uri = $request->getUri()->withPath($path);

        $request = $request->withUri($uri);

        return $next($request);
    }
}
