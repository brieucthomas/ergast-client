<?php

/*
 * (c) Brieuc Thomas <tbrieuc@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ergast\Tests\Integration;

use Ergast\Client;
use Ergast\Model\DriverTable;
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

        $driverTable = $response->getDriverTable();

        $this->assertInstanceOf(DriverTable::class, $driverTable);
        $this->assertSame(2, $driverTable->count());

        $this->assertIsDanielRicciardo($driverTable->find('ricciardo'));
        $this->assertIsNicoRosberg($driverTable->find('rosberg'));
    }
}
