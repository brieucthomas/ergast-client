<?php

/*
 * (c) Brieuc Thomas <tbrieuc@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ergast\Tests\Integration;

use Ergast\Client;
use Ergast\Model\Constructor;
use Ergast\Model\ConstructorTable;
use Ergast\Model\Response;
use Ergast\Tests\TestCase;

/**
 * @group integration
 */
class ConstructorTest extends TestCase
{
    public function testGetConstructor()
    {
        $client = new Client();

        $response = $client->sendRequest('/f1/constructors/benetton');

        $this->assertInstanceOf(Response::class, $response);

        $this->assertResponseMetadata($response, 'http://ergast.com/api/f1/constructors/benetton.xml', 'f1', 30, 0, 1);

        $constructorTable = $response->getConstructorTable();

        $this->assertInstanceOf(ConstructorTable::class, $constructorTable);
        $this->assertSame(1, $constructorTable->count());

        $constructor = $constructorTable->find('benetton');

        $this->assertInstanceOf(Constructor::class, $constructor);
        $this->assertSame('benetton', $constructor->getId());
        $this->assertSame('Benetton', $constructor->getName());
        $this->assertSame('Italian', $constructor->getNationality());
        $this->assertSame('http://en.wikipedia.org/wiki/Benetton_Formula', $constructor->getUrl());
    }
}
