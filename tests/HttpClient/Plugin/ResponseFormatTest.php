<?php

/*
 * (c) Brieuc Thomas <tbrieuc@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ergast\Tests\HttpClient\Plugin;

use Ergast\HttpClient\Plugin\ResponseFormat;
use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

class ResponseFormatTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     */
    public function testPlugin(string $format, string $path, string $expectedPath)
    {
        $request = new Request('GET', $path);
        $next = function (RequestInterface $request) use ($expectedPath) {
            $this->assertSame($expectedPath, $request->getUri()->getPath());
        };
        $first = function () {};

        $plugin = new ResponseFormat($format);
        $plugin->handleRequest($request, $next, $first);
    }

    public function dataProvider(): iterable
    {
        return [
            // standard formats
            ['xml', '/any/path', '/any/path.xml'],
            ['json', '/any/path', '/any/path.json'],

            // replacement
            ['json', '/any/path.xml', '/any/path.json'],

            // remove format
            ['', '/any/path', '/any/path'],
            ['', '/any/path.xml', '/any/path'],
        ];
    }
}
