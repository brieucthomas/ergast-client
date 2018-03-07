<?php

/*
 * (c) Brieuc Thomas <tbrieuc@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ergast\Tests;

use Ergast\Model\Circuit;
use Ergast\Model\CircuitTable;
use Ergast\Model\Constructor;
use Ergast\Model\ConstructorStanding;
use Ergast\Model\ConstructorTable;
use Ergast\Model\Driver;
use Ergast\Model\DriverStanding;
use Ergast\Model\DriverTable;
use Ergast\Model\Lap;
use Ergast\Model\LapsList;
use Ergast\Model\Location;
use Ergast\Model\PitStop;
use Ergast\Model\PitStopsList;
use Ergast\Model\Qualifying;
use Ergast\Model\QualifyingList;
use Ergast\Model\Race;
use Ergast\Model\RaceTable;
use Ergast\Model\Response;
use Ergast\Model\Result;
use Ergast\Model\ResultsList;
use Ergast\Model\SeasonTable;
use Ergast\Model\StandingsList;
use Ergast\Model\StandingsTable;
use Ergast\Model\Status;
use Ergast\Model\StatusTable;
use Ergast\Model\Timing;

class ClientTest extends TestCase
{
    public function testGetSeasons()
    {
        $client = $this->createClient(file_get_contents(__DIR__.'/data/xml/02.xml'));
        $response = $client->sendRequest('/f1/seasons');

        $this->assertInstanceOf(Response::class, $response);
        $this->assertResponseMetadata($response, 'http://ergast.com/api/f1/seasons', 'f1', 30, 0, 3);

        $seasons = $response->getSeasonTable();

        $this->assertInstanceOf(SeasonTable::class, $seasons);

        $this->assertSeason($seasons->find(2003), 2003, 'http://en.wikipedia.org/wiki/2003_Formula_One_season');
        $this->assertSeason($seasons->find(2004), 2004, 'http://en.wikipedia.org/wiki/2004_Formula_One_season');
        $this->assertSeason($seasons->find(2005), 2005, 'http://en.wikipedia.org/wiki/2005_Formula_One_season');
    }

    public function testGetRaces()
    {
        $client = $this->createClient(file_get_contents(__DIR__.'/data/xml/03.xml'));
        $response = $client->sendRequest('/f1/2012/races');

        $this->assertInstanceOf(Response::class, $response);
        $this->assertResponseMetadata($response, 'http://ergast.com/api/f1/2012/races', 'f1', 30, 0, 2);

        $races = $response->getRaceTable();

        $this->assertInstanceOf(RaceTable::class, $races);
        $this->assertContainsOnly(Race::class, $races);
        $this->assertCount(2, $races);

        $iterator = $races->getIterator();
        $iterator->rewind();

        $race = $iterator->current();
        $this->assertSame(2012, $race->getSeason());
        $this->assertSame(1, $race->getRound());
        $this->assertSame('Australian Grand Prix', $race->getName());
        $this->assertSame('2012-03-18', $race->getDate()->format('Y-m-d'));
        $this->assertSame('06:00:00Z', $race->getTime()->format('H:i:sT'));
        $this->assertSame('2012-03-18T06:00:00+0000', $race->getStartDate()->format(\DateTime::ISO8601));
        $this->assertSame('http://en.wikipedia.org/wiki/2012_Australian_Grand_Prix', $race->getUrl());
        $this->assertSame('albert_park', $race->getCircuit()->getId());

        $iterator->next();
        $race = $iterator->current();
        $this->assertSame(2012, $race->getSeason());
        $this->assertSame(2, $race->getRound());
        $this->assertSame('Brazilian Grand Prix', $race->getName());
        $this->assertSame('2012-11-25', $race->getDate()->format('Y-m-d'));
        $this->assertNull($race->getTime());
        $this->assertSame('2012-11-25T00:00:00+0000', $race->getStartDate()->format(\DateTime::ISO8601));
        $this->assertSame('http://en.wikipedia.org/wiki/2012_Brazilian_Grand_Prix', $race->getUrl());
        $this->assertSame('interlagos', $race->getCircuit()->getId());
    }

    public function testGetResults()
    {
        $client = $this->createClient(file_get_contents(__DIR__.'/data/xml/04.xml'));
        $response = $client->sendRequest('/f1/2008/5/results');

        $this->assertInstanceOf(Response::class, $response);
        $this->assertResponseMetadata($response, 'http://ergast.com/api/f1/2008/5/results', 'f1', 30, 0, 1);

        $races = $response->getRaceTable();

        $this->assertInstanceOf(RaceTable::class, $races);
        $this->assertContainsOnly(Race::class, $races);
        $this->assertCount(1, $races);

        $race = $races->first();

        $this->assertSame('Turkish Grand Prix', $race->getName());
        $this->assertSame(2008, $race->getSeason());
        $this->assertSame(5, $race->getRound());
        $this->assertSame('http://en.wikipedia.org/wiki/2008_Turkish_Grand_Prix', $race->getUrl());

        $results = $race->getResultList();

        $this->assertInstanceOf(ResultsList::class, $results);
        $this->assertContainsOnly(Result::class, $results);
        $this->assertCount(1, $results);

        $result = $results->first();

        $this->assertSame('massa', $result->getDriver()->getId());
        $this->assertSame('ferrari', $result->getConstructor()->getId());
        $this->assertSame(1, $result->getPosition());
        $this->assertSame('1', $result->getPositionText());
        $this->assertSame(10.0, $result->getPoints());
        $this->assertSame(1, $result->getGrid());
        $this->assertSame(58, $result->getLaps());
        $this->assertSame(2, $result->getNumber());
        $this->assertSame('Finished', $result->getStatus()->getName());
        $this->assertSame('1:26:49.451', $result->getTime()->getValue());
        $this->assertSame(5209451, $result->getTime()->getMillis());
        $this->assertSame('1:26.666', $result->getFastestLap()->getTime());
        $this->assertSame(3, $result->getFastestLap()->getRank());
        $this->assertSame(16, $result->getFastestLap()->getLap());
        $this->assertSame(221.734, $result->getFastestLap()->getAverageSpeed()->getValue());
        $this->assertSame('kph', $result->getFastestLap()->getAverageSpeed()->getUnits());
    }

    public function testGetQualifying()
    {
        $client = $this->createClient(file_get_contents(__DIR__.'/data/xml/05.xml'));
        $response = $client->sendRequest('/f1/2008/5/qualifying');

        $this->assertInstanceOf(Response::class, $response);
        $this->assertResponseMetadata($response, 'http://ergast.com/api/f1/2008/5/qualifying', 'f1', 30, 0, 1);

        $races = $response->getRaceTable();

        $this->assertInstanceOf(RaceTable::class, $races);
        $this->assertContainsOnly(Race::class, $races);
        $this->assertCount(1, $races);

        $race = $races->first();

        $this->assertSame('Turkish Grand Prix', $race->getName());
        $this->assertSame(2008, $race->getSeason());
        $this->assertSame(5, $race->getRound());

        $qualifyingList = $race->getQualifyingList();

        $this->assertInstanceOf(QualifyingList::class, $qualifyingList);
        $this->assertContainsOnly(Qualifying::class, $qualifyingList);
        $this->assertCount(1, $qualifyingList);

        $qualifying = $qualifyingList->first();

        $this->assertSame('massa', $qualifying->getDriver()->getId());
        $this->assertSame('ferrari', $qualifying->getConstructor()->getId());
        $this->assertSame(1, $qualifying->getPosition());
        $this->assertSame(2, $qualifying->getNumber());
        $this->assertSame('1:25.994', $qualifying->getQ1());
        $this->assertSame('1:26.192', $qualifying->getQ2());
        $this->assertSame('1:27.617', $qualifying->getQ3());
    }

    public function testGetDrivers()
    {
        $client = $this->createClient(file_get_contents(__DIR__.'/data/xml/06.xml'));
        $response = $client->sendRequest('/f1/drivers');

        $this->assertInstanceOf(Response::class, $response);
        $this->assertResponseMetadata($response, 'http://ergast.com/api/f1/drivers', 'f1', 30, 0, 3);

        $drivers = $response->getDriverTable();

        $this->assertInstanceOf(DriverTable::class, $drivers);
        $this->assertContainsOnly(Driver::class, $drivers);
        $this->assertCount(3, $drivers);

        $this->assertIsAyrtonSenna($drivers->all()[0]);
        $this->assertIsRayReed($drivers->all()[1]);
        $this->assertIsLewisHamilton($drivers->all()[2]);
    }

    public function testGetConstructors()
    {
        $client = $this->createClient(file_get_contents(__DIR__.'/data/xml/07.xml'));
        $response = $client->sendRequest('/f1/constructors');

        $this->assertInstanceOf(Response::class, $response);
        $this->assertResponseMetadata($response, 'http://ergast.com/api/f1/constructors', 'f1', 30, 0, 2);

        $constructors = $response->getConstructorTable();

        $this->assertInstanceOf(ConstructorTable::class, $constructors);
        $this->assertContainsOnly(Constructor::class, $constructors);
        $this->assertCount(2, $constructors);

        /** @var Constructor $constructor */
        $constructor = $constructors->all()[0];

        $this->assertSame('arrows', $constructor->getId());
        $this->assertSame('Arrows', $constructor->getName());
        $this->assertSame('British', $constructor->getNationality());
        $this->assertSame('http://en.wikipedia.org/wiki/Arrows', $constructor->getUrl());

        /** @var Constructor $constructor */
        $constructor = $constructors->all()[1];

        $this->assertSame('benetton', $constructor->getId());
        $this->assertSame('Benetton', $constructor->getName());
        $this->assertSame('Italian', $constructor->getNationality());
        $this->assertSame('http://en.wikipedia.org/wiki/Benetton_Formula', $constructor->getUrl());
    }

    public function testGetDriverStandings()
    {
        $client = $this->createClient(file_get_contents(__DIR__.'/data/xml/08.xml'));
        $response = $client->sendRequest('/f1/2008/5/driverstandings');

        $this->assertInstanceOf(Response::class, $response);
        $this->assertResponseMetadata($response, 'http://ergast.com/api/f1/2008/5/driverstandings', 'f1', 30, 0, 2);

        $standingsTable = $response->getStandingsTable();

        $this->assertInstanceOf(StandingsTable::class, $standingsTable);
        $this->assertContainsOnly(StandingsList::class, $standingsTable);
        $this->assertCount(1, $standingsTable);

        $standingsList = $standingsTable->first();

        $this->assertSame(2008, $standingsList->getSeason());
        $this->assertSame(5, $standingsList->getRound());

        $driverStandings = $standingsList->getDriverStandings();

        $this->assertContainsOnly(DriverStanding::class, $driverStandings);
        $this->assertCount(2, $driverStandings);

        /** @var DriverStanding $driverStanding */
        $driverStanding = $driverStandings[0];

        $this->assertSame(1, $driverStanding->getPosition());
        $this->assertSame('1', $driverStanding->getPositionText());
        $this->assertSame(35.0, $driverStanding->getPoints());
        $this->assertSame(2, $driverStanding->getWins());

        /** @var DriverStanding $driverStanding */
        $driverStanding = $driverStandings[1];

        $this->assertSame(2, $driverStanding->getPosition());
        $this->assertSame('2', $driverStanding->getPositionText());
        $this->assertSame(28.0, $driverStanding->getPoints());
        $this->assertSame(2, $driverStanding->getWins());
    }

    public function testGetConstructorStandings()
    {
        $client = $this->createClient(file_get_contents(__DIR__.'/data/xml/09.xml'));
        $response = $client->sendRequest('/f1/2008/5/constructorstandings');

        $this->assertInstanceOf(Response::class, $response);
        $this->assertResponseMetadata($response, 'http://ergast.com/api/f1/2008/5/constructorstandings', 'f1', 30, 0, 2);

        $standingsTable = $response->getStandingsTable();

        $this->assertInstanceOf(StandingsTable::class, $standingsTable);
        $this->assertContainsOnly(StandingsList::class, $standingsTable);
        $this->assertCount(1, $standingsTable);

        $standingsList = $standingsTable->first();

        $this->assertSame(2008, $standingsList->getSeason());
        $this->assertSame(5, $standingsList->getRound());

        $constructorStandings = $standingsList->getConstructorStandings();

        $this->assertContainsOnly(ConstructorStanding::class, $constructorStandings);
        $this->assertCount(2, $constructorStandings);

        /** @var ConstructorStanding $constructorStanding */
        $constructorStanding = $constructorStandings[0];

        $this->assertSame('ferrari', $constructorStanding->getConstructor()->getId());
        $this->assertSame(63.0, $constructorStanding->getPoints());
        $this->assertSame(4, $constructorStanding->getWins());
        $this->assertSame(1, $constructorStanding->getPosition());
        $this->assertSame('1', $constructorStanding->getPositionText());

        /** @var ConstructorStanding $constructorStanding */
        $constructorStanding = $constructorStandings[1];

        $this->assertSame('bmw_sauber', $constructorStanding->getConstructor()->getId());
        $this->assertSame(44.0, $constructorStanding->getPoints());
        $this->assertSame(0, $constructorStanding->getWins());
        $this->assertSame(2, $constructorStanding->getPosition());
        $this->assertSame('2', $constructorStanding->getPositionText());
    }

    public function testGetDriverChampionshipsWinners()
    {
        $client = $this->createClient(file_get_contents(__DIR__.'/data/xml/10.xml'));
        $response = $client->sendRequest('/f1/driverstandings/1');

        $this->assertInstanceOf(Response::class, $response);
        $this->assertResponseMetadata($response, 'http://ergast.com/api/f1/driverstandings/1', 'f1', 30, 0, 2);

        $standingsTable = $response->getStandingsTable();

        $this->assertInstanceOf(StandingsTable::class, $standingsTable);
        $this->assertContainsOnly(StandingsList::class, $standingsTable);
        $this->assertCount(2, $standingsTable);

        $standingsList = $standingsTable->first();

        $this->assertSame(2007, $standingsList->getSeason());
        $this->assertSame(17, $standingsList->getRound());

        $driverStandings = $standingsList->getDriverStandings();

        $this->assertContainsOnly(DriverStanding::class, $driverStandings);
        $this->assertCount(1, $driverStandings);

        /** @var DriverStanding $driverStanding */
        $driverStanding = $driverStandings[0];

        $this->assertSame('raikkonen', $driverStanding->getDriver()->getId());
        $this->assertSame('ferrari', $driverStanding->getConstructor()->getId());
        $this->assertSame(110.0, $driverStanding->getPoints());
        $this->assertSame(6, $driverStanding->getWins());
        $this->assertSame(1, $driverStanding->getPosition());
        $this->assertSame('1', $driverStanding->getPositionText());

        $standingsList = $standingsTable->all()[1];

        $this->assertSame(2008, $standingsList->getSeason());
        $this->assertSame(18, $standingsList->getRound());

        $driverStandings = $standingsList->getDriverStandings();

        $this->assertContainsOnly(DriverStanding::class, $driverStandings);
        $this->assertCount(1, $driverStandings);

        /** @var DriverStanding $driverStanding */
        $driverStanding = $driverStandings[0];

        $this->assertSame('hamilton', $driverStanding->getDriver()->getId());
        $this->assertSame('mclaren', $driverStanding->getConstructor()->getId());
        $this->assertSame(98.0, $driverStanding->getPoints());
        $this->assertSame(5, $driverStanding->getWins());
        $this->assertSame(1, $driverStanding->getPosition());
        $this->assertSame('1', $driverStanding->getPositionText());
    }

    public function testGetCircuits()
    {
        $client = $this->createClient(file_get_contents(__DIR__.'/data/xml/11.xml'));
        $response = $client->sendRequest('/f1/circuits');

        $this->assertInstanceOf(Response::class, $response);
        $this->assertResponseMetadata($response, 'http://ergast.com/api/f1/circuits', 'f1', 30, 0, 2);

        $this->assertInstanceOf(CircuitTable::class, $response->getCircuitTable());
        $this->assertContainsOnly(Circuit::class, $response->getCircuitTable());
        $this->assertCount(2, $response->getCircuitTable());

        $circuits = $response->getCircuitTable()->all();

        /** @var Circuit $circuit */
        $circuit = $circuits[0];

        $this->assertSame('monza', $circuit->getId());
        $this->assertSame('Autodromo Nazionale di Monza', $circuit->getName());
        $this->assertSame('http://en.wikipedia.org/wiki/Autodromo_Nazionale_Monza', $circuit->getUrl());
        $this->assertInstanceOf(Location::class, $circuit->getLocation());
        $this->assertSame('Monza', $circuit->getLocation()->getLocality());
        $this->assertSame('Italy', $circuit->getLocation()->getCountry());
        $this->assertSame(45.6156, $circuit->getLocation()->getLatitude());
        $this->assertSame(9.28111, $circuit->getLocation()->getLongitude());

        /** @var Circuit $circuit */
        $circuit = $circuits[1];

        $this->assertSame('zolder', $circuit->getId());
        $this->assertSame('Zolder', $circuit->getName());
        $this->assertSame('http://en.wikipedia.org/wiki/Zolder', $circuit->getUrl());
        $this->assertInstanceOf(Location::class, $circuit->getLocation());
        $this->assertSame('Heusden-Zolder', $circuit->getLocation()->getLocality());
        $this->assertSame('Belgium', $circuit->getLocation()->getCountry());
        $this->assertSame(50.9894, $circuit->getLocation()->getLatitude());
        $this->assertSame(5.25694, $circuit->getLocation()->getLongitude());
    }

    public function testGetFinishingStatus()
    {
        $client = $this->createClient(file_get_contents(__DIR__.'/data/xml/12.xml'));
        $response = $client->sendRequest('/f1/status');

        $this->assertInstanceOf(Response::class, $response);
        $this->assertResponseMetadata($response, 'http://ergast.com/api/f1/status', 'f1', 30, 0, 3);

        $this->assertInstanceOf(StatusTable::class, $response->getStatusTable());
        $this->assertContainsOnly(Status::class, $response->getStatusTable());
        $this->assertCount(3, $response->getStatusTable());

        $statuses = $response->getStatusTable()->all();

        /** @var Status $status */
        $status = $statuses[0];

        $this->assertSame(1, $status->getId());
        $this->assertSame('Finished', $status->getName());
        $this->assertSame(5655, $status->getCount());

        /** @var Status $status */
        $status = $statuses[1];

        $this->assertSame(2, $status->getId());
        $this->assertSame('Disqualified', $status->getName());
        $this->assertSame(137, $status->getCount());

        /** @var Status $status */
        $status = $statuses[2];

        $this->assertSame(3, $status->getId());
        $this->assertSame('Accident', $status->getName());
        $this->assertSame(993, $status->getCount());
    }

    public function testGetLapTimes()
    {
        $client = $this->createClient(file_get_contents(__DIR__.'/data/xml/13.xml'));
        $response = $client->sendRequest('/f1/2011/5/laps');

        $this->assertInstanceOf(Response::class, $response);
        $this->assertResponseMetadata($response, 'http://ergast.com/api/f1/2011/5/laps', 'f1', 30, 0, 2);

        $race = $response->getRaceTable()->first();

        $this->assertInstanceOf(Race::class, $race);
        $this->assertSame(2011, $race->getSeason());
        $this->assertSame(5, $race->getRound());
        $this->assertSame('http://en.wikipedia.org/wiki/2011_Spanish_Grand_Prix', $race->getUrl());

        $this->assertInstanceOf(LapsList::class, $race->getLapsList());
        $this->assertContainsOnly(Lap::class, $race->getLapsList());
        $this->assertCount(2, $race->getLapsList());

        $lapsList = $race->getLapsList()->all();

        /** @var Lap $lap */
        $lap = $lapsList[0];

        $this->assertSame(1, $lap->getNumber());
        $this->assertContainsOnly(Timing::class, $lap->getTiming());
        $this->assertCount(2, $lap->getTiming());

        /** @var Timing $timing */
        $timing = $lap->getTiming()[0];

        $this->assertSame(1, $timing->getLap());
        $this->assertSame(1, $timing->getPosition());
        $this->assertSame('alonso', $timing->getDriverId());
        $this->assertSame('1:34.494', $timing->getTime());

        /** @var Timing $timing */
        $timing = $lap->getTiming()[1];

        $this->assertSame(1, $timing->getLap());
        $this->assertSame(2, $timing->getPosition());
        $this->assertSame('vettel', $timing->getDriverId());
        $this->assertSame('1:35.274', $timing->getTime());

        /** @var Lap $lap */
        $lap = $lapsList[1];

        $this->assertSame(2, $lap->getNumber());
        $this->assertContainsOnly(Timing::class, $lap->getTiming());
        $this->assertCount(2, $lap->getTiming());

        /** @var Timing $timing */
        $timing = $lap->getTiming()[0];

        $this->assertSame(2, $timing->getLap());
        $this->assertSame(1, $timing->getPosition());
        $this->assertSame('alonso', $timing->getDriverId());
        $this->assertSame('1:30.812', $timing->getTime());

        /** @var Timing $timing */
        $timing = $lap->getTiming()[1];

        $this->assertSame(2, $timing->getLap());
        $this->assertSame(2, $timing->getPosition());
        $this->assertSame('vettel', $timing->getDriverId());
        $this->assertSame('1:30.633', $timing->getTime());
    }

    public function testGetPitStops()
    {
        $client = $this->createClient(file_get_contents(__DIR__.'/data/xml/14.xml'));
        $response = $client->sendRequest('/f1/2011/5/pitstops');

        $this->assertInstanceOf(Response::class, $response);
        $this->assertResponseMetadata($response, 'http://ergast.com/api/f1/2011/5/pitstops', 'f1', 30, 0, 2);

        $race = $response->getRaceTable()->first();

        $this->assertInstanceOf(Race::class, $race);
        $this->assertSame(2011, $race->getSeason());
        $this->assertSame(5, $race->getRound());
        $this->assertSame('http://en.wikipedia.org/wiki/2011_Spanish_Grand_Prix', $race->getUrl());

        $this->assertInstanceOf(PitStopsList::class, $race->getPitStopsList());
        $this->assertContainsOnly(PitStop::class, $race->getPitStopsList());
        $this->assertCount(2, $race->getPitStopsList());

        $pitStops = $race->getPitStopsList()->all();

        /** @var PitStop $pitStop */
        $pitStop = $pitStops[0];

        $this->assertSame('kobayashi', $pitStop->getDriverId());
        $this->assertSame(24.871, $pitStop->getDuration());
        $this->assertSame('14:05:11', $pitStop->getTime()->format('H:i:s'));
        $this->assertSame(1, $pitStop->getStop());
        $this->assertSame(1, $pitStop->getLap());

        /** @var PitStop $pitStop */
        $pitStop = $pitStops[1];

        $this->assertSame('perez', $pitStop->getDriverId());
        $this->assertSame(23.592, $pitStop->getDuration());
        $this->assertSame('14:14:14', $pitStop->getTime()->format('H:i:s'));
        $this->assertSame(1, $pitStop->getStop());
        $this->assertSame(7, $pitStop->getLap());
    }

    public function testShowFormulaOneDriversWhoHaveWonTheRaceAtBaku()
    {
        $client = $this->createClient(file_get_contents(__DIR__.'/data/xml/01.xml'));
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
