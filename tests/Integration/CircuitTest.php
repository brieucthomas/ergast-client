<?php

/*
 * (c) Brieuc Thomas <tbrieuc@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ergast\Tests\Integration;

use Ergast\Client;
use Ergast\Model\Circuit;
use Ergast\Model\CircuitTable;
use Ergast\Model\Location;
use Ergast\Model\Response;
use Ergast\Tests\TestCase;

/**
 * @group integration
 */
class CircuitTest extends TestCase
{
    public function testGetCircuit()
    {
        $client = new Client();

        $response = $client->sendRequest('/f1/circuits/monza');

        $this->assertInstanceOf(Response::class, $response);

        $this->assertResponseMetadata($response, 'http://ergast.com/api/f1/circuits/monza.xml', 'f1', 30, 0, 1);

        $circuitTable = $response->getCircuitTable();

        $this->assertInstanceOf(CircuitTable::class, $circuitTable);
        $this->assertSame(1, $circuitTable->count());

        $circuit = $circuitTable->find('monza');

        $this->assertInstanceOf(Circuit::class, $circuit);
        $this->assertSame('monza', $circuit->getId());
        $this->assertSame('Autodromo Nazionale di Monza', $circuit->getName());
        $this->assertSame('http://en.wikipedia.org/wiki/Autodromo_Nazionale_Monza', $circuit->getUrl());
        $this->assertInstanceOf(Location::class, $circuit->getLocation());
        $this->assertSame('Monza', $circuit->getLocation()->getLocality());
        $this->assertSame('Italy', $circuit->getLocation()->getCountry());
        $this->assertSame(45.6156, $circuit->getLocation()->getLatitude());
        $this->assertSame(9.28111, $circuit->getLocation()->getLongitude());
    }
}
