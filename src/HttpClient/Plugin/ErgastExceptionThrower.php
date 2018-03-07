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
 * @author Brieuc Thomas <tbrieuc@gmail.com>
 */
class ErgastExceptionThrower implements Plugin
{
    public function handleRequest(RequestInterface $request, callable $next, callable $first)
    {
        return $next($request);
    }
}
