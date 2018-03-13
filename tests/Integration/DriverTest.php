<?php

/*
 * (c) Brieuc Thomas <tbrieuc@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ergast\Tests\Integration;

use Doctrine\Common\Collections\Collection;
use Ergast\Client;
use Ergast\Model\Response;
use Ergast\Tests\TestCase;

/**
 * @group integration
 */
class DriverTest extends TestCase
{
    public function testListFormulaOneDriversWhoHaveWonTheRaceAtBaku()
    {
        $client = new Client();

        $response = $client->sendRequest('/f1/circuits/bak/results/1/drivers');

        $this->assertInstanceOf(Response::class, $response);

        $this->assertResponseMetadata($response, 'http://ergast.com/api/f1/circuits/bak/results/1/drivers.xml', 'f1', 30, 0, 2);

        $drivers = $response->getDrivers();

        $this->assertInstanceOf(Collection::class, $drivers);
        $this->assertSame(2, $drivers->count());

        $this->assertIsDanielRicciardo($drivers->get(0));
        $this->assertIsNicoRosberg($drivers->get(1));
    }
}
