<?php

/*
 * (c) Brieuc Thomas <tbrieuc@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\BrieucThomas\ErgastClient;

use BrieucThomas\ErgastClient\ErgastClient;
use BrieucThomas\ErgastClient\Model\Response as ErgastResponse;
use GuzzleHttp\Psr7\Response as HttpResponse;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\ClientInterface;
use JMS\Serializer\SerializerBuilder;

class ErgastClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException BrieucThomas\ErgastClient\Exception\BadResponseFormatException
     * @expectedExceptionMessage Supported response formats are application/xml, got application/json.
     */
    public function testDeserializeUnsupportedFormatResponseThrowsException()
    {
        $httpResponse = $this->createHttpResponse('{}', 'application/json; charset=utf-8');
        $this->deserializeHttpResponse($httpResponse);
    }

    public function testDeserializeEmptyResponseHasEmptyCollections()
    {
        $httpResponse = $this->createHttpResponseFromFile('empty.xml', 'application/xml; charset=utf-8');
        $ergastResponse = $this->deserializeHttpResponse($httpResponse);

        $this->assertInstanceOf('BrieucThomas\ErgastClient\Model\Response', $ergastResponse);
        $this->assertEmpty($ergastResponse->getSeasons());
        $this->assertEmpty($ergastResponse->getRaces());
        $this->assertEmpty($ergastResponse->getCircuits());
        $this->assertEmpty($ergastResponse->getConstructors());
        $this->assertEmpty($ergastResponse->getFinishingStatues());
        $this->assertEmpty($ergastResponse->getStandings());
        $this->assertEmpty($ergastResponse->getDrivers());
        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $ergastResponse->getSeasons());
        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $ergastResponse->getRaces());
        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $ergastResponse->getCircuits());
        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $ergastResponse->getConstructors());
        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $ergastResponse->getFinishingStatues());
        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $ergastResponse->getStandings());
        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $ergastResponse->getDrivers());
    }

    public function testDeserializeSeasons()
    {
        $httpResponse = $this->createHttpResponseFromFile('seasons.xml', 'application/xml; charset=utf-8');
        $ergastResponse = $this->deserializeHttpResponse($httpResponse);

        $this->assertInstanceOf('BrieucThomas\ErgastClient\Model\Response', $ergastResponse);
        $this->assertSame('http://ergast.com/api/f1/seasons', $ergastResponse->getUrl());
        $this->assertSame('f1', $ergastResponse->getSeries());
        $this->assertSame(3, $ergastResponse->getTotal());
        $this->assertSame(30, $ergastResponse->getLimit());
        $this->assertSame(0, $ergastResponse->getOffset());
        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $ergastResponse->getSeasons());
        $this->assertCount(3, $ergastResponse->getSeasons());

        $season = $ergastResponse->getSeasons()->first();
        $this->assertInstanceOf('BrieucThomas\ErgastClient\Model\Season', $season);
        $this->assertSame(2003, $season->getYear());
        $this->assertSame('http://en.wikipedia.org/wiki/2003_Formula_One_season', $season->getUrl());

        $season = $ergastResponse->getSeasons()->next();
        $this->assertInstanceOf('BrieucThomas\ErgastClient\Model\Season', $season);
        $this->assertSame(2004, $season->getYear());
        $this->assertSame('http://en.wikipedia.org/wiki/2004_Formula_One_season', $season->getUrl());

        $season = $ergastResponse->getSeasons()->next();
        $this->assertInstanceOf('BrieucThomas\ErgastClient\Model\Season', $season);
        $this->assertSame(2005, $season->getYear());
        $this->assertSame('http://en.wikipedia.org/wiki/2005_Formula_One_season', $season->getUrl());
    }

    public function testDeserializeRaces()
    {
        $httpResponse = $this->createHttpResponseFromFile('races.xml', 'application/xml; charset=utf-8');
        $ergastResponse = $this->deserializeHttpResponse($httpResponse);

        $this->assertInstanceOf('BrieucThomas\ErgastClient\Model\Response', $ergastResponse);
        $this->assertSame('http://ergast.com/api/f1/2012/races', $ergastResponse->getUrl());
        $this->assertSame('f1', $ergastResponse->getSeries());
        $this->assertSame(2, $ergastResponse->getTotal());
        $this->assertSame(30, $ergastResponse->getLimit());
        $this->assertSame(0, $ergastResponse->getOffset());
        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $ergastResponse->getRaces());
        $this->assertCount(2, $ergastResponse->getRaces());

        $race = $ergastResponse->getRaces()->first();
        $this->assertInstanceOf('BrieucThomas\ErgastClient\Model\Race', $race);
        $this->assertSame(2012, $race->getSeason());
        $this->assertSame(1, $race->getRound());
        $this->assertSame('Australian Grand Prix', $race->getName());
        $this->assertSame('2012-03-18', $race->getDate()->format('Y-m-d'));
        $this->assertSame('06:00:00Z', $race->getTime()->format('H:i:sT'));
        $this->assertSame('2012-03-18T06:00:00+0000', $race->getStartDate()->format(\DateTime::ISO8601));
        $this->assertSame('http://en.wikipedia.org/wiki/2012_Australian_Grand_Prix', $race->getUrl());
        $this->assertSame('albert_park', $race->getCircuit()->getId());

        $race = $ergastResponse->getRaces()->next();
        $this->assertInstanceOf('BrieucThomas\ErgastClient\Model\Race', $race);
        $this->assertSame(2012, $race->getSeason());
        $this->assertSame(2, $race->getRound());
        $this->assertSame('Brazilian Grand Prix', $race->getName());
        $this->assertSame('2012-11-25', $race->getDate()->format('Y-m-d'));
        $this->assertNull($race->getTime());
        $this->assertSame('2012-11-25T00:00:00+0000', $race->getStartDate()->format(\DateTime::ISO8601));
        $this->assertSame('http://en.wikipedia.org/wiki/2012_Brazilian_Grand_Prix', $race->getUrl());
        $this->assertSame('interlagos', $race->getCircuit()->getId());
    }

    public function testDeserializeResults()
    {
        $httpResponse = $this->createHttpResponseFromFile('results.xml', 'application/xml; charset=utf-8');
        $ergastResponse = $this->deserializeHttpResponse($httpResponse);

        $this->assertInstanceOf('BrieucThomas\ErgastClient\Model\Response', $ergastResponse);
        $this->assertSame('http://ergast.com/api/f1/2008/5/results', $ergastResponse->getUrl());
        $this->assertSame('f1', $ergastResponse->getSeries());
        $this->assertSame(30, $ergastResponse->getLimit());
        $this->assertSame(0, $ergastResponse->getOffset());
        $this->assertSame(1, $ergastResponse->getTotal());

        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $ergastResponse->getRaces());
        $this->assertCount(1, $ergastResponse->getRaces());
        $race = $ergastResponse->getRaces()->first();
        $this->assertInstanceOf('BrieucThomas\ErgastClient\Model\Race', $race);
        $this->assertSame('Turkish Grand Prix', $race->getName());
        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $race->getResults());
        $this->assertCount(1, $race->getResults());

        $result = $race->getResults()->first();
        $this->assertInstanceOf('BrieucThomas\ErgastClient\Model\Result', $result);
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

    public function testDeserializeQualifying()
    {
        $httpResponse = $this->createHttpResponseFromFile('qualifying.xml', 'application/xml; charset=utf-8');
        $ergastResponse = $this->deserializeHttpResponse($httpResponse);

        $this->assertInstanceOf('BrieucThomas\ErgastClient\Model\Response', $ergastResponse);
        $this->assertSame('http://ergast.com/api/f1/2008/5/qualifying', $ergastResponse->getUrl());
        $this->assertSame('f1', $ergastResponse->getSeries());
        $this->assertSame(30, $ergastResponse->getLimit());
        $this->assertSame(0, $ergastResponse->getOffset());
        $this->assertSame(1, $ergastResponse->getTotal());
        $this->assertCount(1, $ergastResponse->getRaces());

        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $ergastResponse->getRaces());
        $this->assertCount(1, $ergastResponse->getRaces());
        $race = $ergastResponse->getRaces()->first();
        $this->assertInstanceOf('BrieucThomas\ErgastClient\Model\Race', $race);
        $this->assertSame('Turkish Grand Prix', $race->getName());
        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $race->getQualifying());
        $this->assertCount(1, $race->getQualifying());

        $this->assertSame('Turkish Grand Prix', $race->getName());
        $this->assertCount(1, $race->getQualifying());

        $qualifying = $race->getQualifying()->first();
        $this->assertInstanceOf('BrieucThomas\ErgastClient\Model\Qualifying', $qualifying);

        $this->assertSame('massa', $qualifying->getDriver()->getId());
        $this->assertSame('ferrari', $qualifying->getConstructor()->getId());
        $this->assertSame(1, $qualifying->getPosition());
        $this->assertSame(2, $qualifying->getNumber());
        $this->assertSame('1:25.994', $qualifying->getQ1());
        $this->assertSame('1:26.192', $qualifying->getQ2());
        $this->assertSame('1:27.617', $qualifying->getQ3());
    }

    public function testDeserializeDrivers()
    {
        $httpResponse = $this->createHttpResponseFromFile('drivers.xml', 'application/xml; charset=utf-8');
        $ergastResponse = $this->deserializeHttpResponse($httpResponse);

        $this->assertInstanceOf('BrieucThomas\ErgastClient\Model\Response', $ergastResponse);
        $this->assertSame('http://ergast.com/api/f1/drivers', $ergastResponse->getUrl());
        $this->assertSame('f1', $ergastResponse->getSeries());
        $this->assertSame(3, $ergastResponse->getTotal());
        $this->assertSame(30, $ergastResponse->getLimit());
        $this->assertSame(0, $ergastResponse->getOffset());
        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $ergastResponse->getDrivers());
        $this->assertCount(3, $ergastResponse->getDrivers());

        $driver = $ergastResponse->getDrivers()->first();
        $this->assertInstanceOf('BrieucThomas\ErgastClient\Model\Driver', $driver);
        $this->assertSame('senna', $driver->getId());
        $this->assertEmpty($driver->getCode());
        $this->assertSame('Ayrton', $driver->getGivenName());
        $this->assertSame('Senna', $driver->getFamilyName());
        $this->assertInstanceOf('\DateTime', $driver->getBirthDate());
        $this->assertSame('1960-03-21T00:00:00+0000', $driver->getBirthDate()->format(\DateTime::ISO8601));
        $this->assertSame('Brazilian', $driver->getNationality());
        $this->assertNull($driver->getNumber());
        $this->assertSame('http://en.wikipedia.org/wiki/Ayrton_Senna', $driver->getUrl());

        $driver = $ergastResponse->getDrivers()->next();
        $this->assertInstanceOf('BrieucThomas\ErgastClient\Model\Driver', $driver);
        $this->assertSame('reed', $driver->getId());
        $this->assertEmpty($driver->getCode());
        $this->assertSame('Ray', $driver->getGivenName());
        $this->assertSame('Reed', $driver->getFamilyName());
        $this->assertNull($driver->getBirthDate());
        $this->assertSame('South African', $driver->getNationality());
        $this->assertNull($driver->getNumber());
        $this->assertSame('http://en.wikipedia.org/wiki/Ray_Reed', $driver->getUrl());

        $driver = $ergastResponse->getDrivers()->next();
        $this->assertInstanceOf('BrieucThomas\ErgastClient\Model\Driver', $driver);
        $this->assertSame('hamilton', $driver->getId());
        $this->assertSame('HAM', $driver->getCode());
        $this->assertSame('Lewis', $driver->getGivenName());
        $this->assertSame('Hamilton', $driver->getFamilyName());
        $this->assertInstanceOf('\DateTime', $driver->getBirthDate());
        $this->assertSame('1985-01-07T00:00:00+0000', $driver->getBirthDate()->format(\DateTime::ISO8601));
        $this->assertSame('British', $driver->getNationality());
        $this->assertSame(44, $driver->getNumber());
        $this->assertSame('http://en.wikipedia.org/wiki/Lewis_Hamilton', $driver->getUrl());
    }

    public function testDeserializeConstructors()
    {
        $httpResponse = $this->createHttpResponseFromFile('constructors.xml', 'application/xml; charset=utf-8');
        $ergastResponse = $this->deserializeHttpResponse($httpResponse);

        $this->assertInstanceOf('BrieucThomas\ErgastClient\Model\Response', $ergastResponse);
        $this->assertSame('http://ergast.com/api/f1/constructors', $ergastResponse->getUrl());
        $this->assertSame('f1', $ergastResponse->getSeries());
        $this->assertSame(2, $ergastResponse->getTotal());
        $this->assertSame(30, $ergastResponse->getLimit());
        $this->assertSame(0, $ergastResponse->getOffset());
        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $ergastResponse->getConstructors());
        $this->assertCount(2, $ergastResponse->getConstructors());

        $constructor = $ergastResponse->getConstructors()->first();
        $this->assertInstanceOf('BrieucThomas\ErgastClient\Model\Constructor', $constructor);
        $this->assertSame('arrows', $constructor->getId());
        $this->assertSame('Arrows', $constructor->getName());
        $this->assertSame('British', $constructor->getNationality());
        $this->assertSame('http://en.wikipedia.org/wiki/Arrows', $constructor->getUrl());

        $constructor = $ergastResponse->getConstructors()->next();
        $this->assertInstanceOf('BrieucThomas\ErgastClient\Model\Constructor', $constructor);
        $this->assertSame('benetton', $constructor->getId());
        $this->assertSame('Benetton', $constructor->getName());
        $this->assertSame('Italian', $constructor->getNationality());
        $this->assertSame('http://en.wikipedia.org/wiki/Benetton_Formula', $constructor->getUrl());
    }

    public function testDeserializeDriverStandings()
    {
        $httpResponse = $this->createHttpResponseFromFile('driver_standings.xml', 'application/xml; charset=utf-8');
        $ergastResponse = $this->deserializeHttpResponse($httpResponse);

        $this->assertInstanceOf('BrieucThomas\ErgastClient\Model\Response', $ergastResponse);
        $this->assertSame('http://ergast.com/api/f1/2008/5/driverstandings', $ergastResponse->getUrl());
        $this->assertSame('f1', $ergastResponse->getSeries());
        $this->assertSame(2, $ergastResponse->getTotal());
        $this->assertSame(30, $ergastResponse->getLimit());
        $this->assertSame(0, $ergastResponse->getOffset());
        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $ergastResponse->getStandings());
        $this->assertCount(1, $ergastResponse->getStandings());

        $standings = $ergastResponse->getStandings()->first();
        $this->assertInstanceOf('BrieucThomas\ErgastClient\Model\Standings', $standings);
        $this->assertSame(2008, $standings->getSeason());
        $this->assertSame(5, $standings->getRound());
        $this->assertCount(2, $standings->getDriverStandings());

        $driverStandings = $standings->getDriverStandings()->first();
        $this->assertInstanceOf('BrieucThomas\ErgastClient\Model\DriverStanding', $driverStandings);
        $this->assertSame('raikkonen', $driverStandings->getDriver()->getId());
        $this->assertSame('ferrari', $driverStandings->getConstructor()->getId());
        $this->assertSame(35.0, $driverStandings->getPoints());
        $this->assertSame(2, $driverStandings->getWins());
        $this->assertSame(1, $driverStandings->getPosition());
        $this->assertSame('1', $driverStandings->getPositionText());

        $driverStanding = $standings->getDriverStandings()->next();
        $this->assertInstanceOf('BrieucThomas\ErgastClient\Model\DriverStanding', $driverStandings);
        $this->assertSame('massa', $driverStanding->getDriver()->getId());
        $this->assertSame('ferrari', $driverStanding->getConstructor()->getId());
        $this->assertSame(28.0, $driverStanding->getPoints());
        $this->assertSame(2, $driverStanding->getWins());
        $this->assertSame(2, $driverStanding->getPosition());
        $this->assertSame('2', $driverStanding->getPositionText());
    }

    public function testDeserializeConstructorStandings()
    {
        $httpResponse = $this->createHttpResponseFromFile('constructor_standings.xml', 'application/xml; charset=utf-8');
        $ergastResponse = $this->deserializeHttpResponse($httpResponse);

        $this->assertInstanceOf('BrieucThomas\ErgastClient\Model\Response', $ergastResponse);
        $this->assertSame('http://ergast.com/api/f1/2008/5/constructorstandings', $ergastResponse->getUrl());
        $this->assertSame('f1', $ergastResponse->getSeries());
        $this->assertSame(2, $ergastResponse->getTotal());
        $this->assertSame(30, $ergastResponse->getLimit());
        $this->assertSame(0, $ergastResponse->getOffset());
        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $ergastResponse->getStandings());
        $this->assertCount(1, $ergastResponse->getStandings());

        $standings = $ergastResponse->getStandings()->first();
        $this->assertInstanceOf('BrieucThomas\ErgastClient\Model\Standings', $standings);
        $this->assertSame(2008, $standings->getSeason());
        $this->assertSame(5, $standings->getRound());
        $this->assertCount(2, $standings->getConstructorStandings());

        $constructorStanding = $standings->getConstructorStandings()->first();
        $this->assertInstanceOf('BrieucThomas\ErgastClient\Model\ConstructorStanding', $constructorStanding);
        $this->assertSame('ferrari', $constructorStanding->getConstructor()->getId());
        $this->assertSame(63.0, $constructorStanding->getPoints());
        $this->assertSame(4, $constructorStanding->getWins());
        $this->assertSame(1, $constructorStanding->getPosition());
        $this->assertSame('1', $constructorStanding->getPositionText());

        $constructorStanding = $standings->getConstructorStandings()->next();
        $this->assertInstanceOf('BrieucThomas\ErgastClient\Model\ConstructorStanding', $constructorStanding);
        $this->assertSame('bmw_sauber', $constructorStanding->getConstructor()->getId());
        $this->assertSame(44.0, $constructorStanding->getPoints());
        $this->assertSame(0, $constructorStanding->getWins());
        $this->assertSame(2, $constructorStanding->getPosition());
        $this->assertSame('2', $constructorStanding->getPositionText());
    }

    public function testDeserializeDriverChampionshipsWinners()
    {
        $httpResponse = $this->createHttpResponseFromFile('driver_world_champions.xml', 'application/xml; charset=utf-8');
        $ergastResponse = $this->deserializeHttpResponse($httpResponse);

        $this->assertInstanceOf('BrieucThomas\ErgastClient\Model\Response', $ergastResponse);
        $this->assertSame('http://ergast.com/api/f1/driverstandings/1', $ergastResponse->getUrl());
        $this->assertSame('f1', $ergastResponse->getSeries());
        $this->assertSame(2, $ergastResponse->getTotal());
        $this->assertSame(30, $ergastResponse->getLimit());
        $this->assertSame(0, $ergastResponse->getOffset());
        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $ergastResponse->getStandings());
        $this->assertCount(2, $ergastResponse->getStandings());

        $standing = $ergastResponse->getStandings()->first();
        $this->assertInstanceOf('BrieucThomas\ErgastClient\Model\Standings', $standing);
        $this->assertSame(2007, $standing->getSeason());
        $this->assertSame(17, $standing->getRound());
        $this->assertCount(1, $standing->getDriverStandings());
        $driverStanding = $standing->getDriverStandings()->first();
        $this->assertInstanceOf('BrieucThomas\ErgastClient\Model\DriverStanding', $driverStanding);
        $this->assertSame('raikkonen', $driverStanding->getDriver()->getId());
        $this->assertSame('ferrari', $driverStanding->getConstructor()->getId());
        $this->assertSame(110.0, $driverStanding->getPoints());
        $this->assertSame(6, $driverStanding->getWins());
        $this->assertSame(1, $driverStanding->getPosition());
        $this->assertSame('1', $driverStanding->getPositionText());

        $standing = $ergastResponse->getStandings()->next();
        $this->assertInstanceOf('BrieucThomas\ErgastClient\Model\Standings', $standing);
        $this->assertSame(2008, $standing->getSeason());
        $this->assertSame(18, $standing->getRound());
        $this->assertCount(1, $standing->getDriverStandings());
        $driverStanding = $standing->getDriverStandings()->first();
        $this->assertInstanceOf('BrieucThomas\ErgastClient\Model\DriverStanding', $driverStanding);
        $this->assertSame('hamilton', $driverStanding->getDriver()->getId());
        $this->assertSame('mclaren', $driverStanding->getConstructor()->getId());
        $this->assertSame(98.0, $driverStanding->getPoints());
        $this->assertSame(5, $driverStanding->getWins());
        $this->assertSame(1, $driverStanding->getPosition());
        $this->assertSame('1', $driverStanding->getPositionText());
    }

    public function testDeserializeCircuits()
    {
        $httpResponse = $this->createHttpResponseFromFile('circuits.xml', 'application/xml; charset=utf-8');
        $ergastResponse = $this->deserializeHttpResponse($httpResponse);

        $this->assertInstanceOf('BrieucThomas\ErgastClient\Model\Response', $ergastResponse);
        $this->assertSame('http://ergast.com/api/f1/circuits', $ergastResponse->getUrl());
        $this->assertSame('f1', $ergastResponse->getSeries());
        $this->assertSame(2, $ergastResponse->getTotal());
        $this->assertSame(30, $ergastResponse->getLimit());
        $this->assertSame(0, $ergastResponse->getOffset());
        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $ergastResponse->getCircuits());
        $this->assertCount(2, $ergastResponse->getCircuits());

        $circuit = $ergastResponse->getCircuits()->first();
        $this->assertInstanceOf('BrieucThomas\ErgastClient\Model\Circuit', $circuit);
        $this->assertSame('monza', $circuit->getId());
        $this->assertSame('Autodromo Nazionale di Monza', $circuit->getName());
        $this->assertSame('http://en.wikipedia.org/wiki/Autodromo_Nazionale_Monza', $circuit->getUrl());
        $this->assertInstanceOf('BrieucThomas\ErgastClient\Model\Location', $circuit->getLocation());
        $this->assertSame('Monza', $circuit->getLocation()->getLocality());
        $this->assertSame('Italy', $circuit->getLocation()->getCountry());
        $this->assertSame(45.6156, $circuit->getLocation()->getLatitude());
        $this->assertSame(9.28111, $circuit->getLocation()->getLongitude());
        $this->assertNull($circuit->getLocation()->getAltitude());

        $circuit = $ergastResponse->getCircuits()->next();
        $this->assertInstanceOf('BrieucThomas\ErgastClient\Model\Circuit', $circuit);
        $this->assertSame('zolder', $circuit->getId());
        $this->assertSame('Zolder', $circuit->getName());
        $this->assertSame('http://en.wikipedia.org/wiki/Zolder', $circuit->getUrl());
        $this->assertInstanceOf('BrieucThomas\ErgastClient\Model\Location', $circuit->getLocation());
        $this->assertSame('Heusden-Zolder', $circuit->getLocation()->getLocality());
        $this->assertSame('Belgium', $circuit->getLocation()->getCountry());
        $this->assertSame(50.9894, $circuit->getLocation()->getLatitude());
        $this->assertSame(5.25694, $circuit->getLocation()->getLongitude());
        $this->assertSame(45.615, $circuit->getLocation()->getAltitude());
    }

    public function testDeserializeFinishingStatus()
    {
        $httpResponse = $this->createHttpResponseFromFile('finishing_statues.xml', 'application/xml; charset=utf-8');
        $ergastResponse = $this->deserializeHttpResponse($httpResponse);

        $this->assertInstanceOf('BrieucThomas\ErgastClient\Model\Response', $ergastResponse);
        $this->assertSame('http://ergast.com/api/f1/status', $ergastResponse->getUrl());
        $this->assertSame('f1', $ergastResponse->getSeries());
        $this->assertSame(3, $ergastResponse->getTotal());
        $this->assertSame(30, $ergastResponse->getLimit());
        $this->assertSame(0, $ergastResponse->getOffset());
        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $ergastResponse->getFinishingStatues());
        $this->assertCount(3, $ergastResponse->getFinishingStatues());

        $status = $ergastResponse->getFinishingStatues()->first();
        $this->assertInstanceOf('BrieucThomas\ErgastClient\Model\FinishingStatus', $status);
        $this->assertSame(1, $status->getId());
        $this->assertSame('Finished', $status->getName());
        $this->assertSame(5655, $status->getCount());

        $status = $ergastResponse->getFinishingStatues()->next();
        $this->assertInstanceOf('BrieucThomas\ErgastClient\Model\FinishingStatus', $status);
        $this->assertSame(2, $status->getId());
        $this->assertSame('Disqualified', $status->getName());
        $this->assertSame(137, $status->getCount());

        $status = $ergastResponse->getFinishingStatues()->next();
        $this->assertInstanceOf('BrieucThomas\ErgastClient\Model\FinishingStatus', $status);
        $this->assertSame(3, $status->getId());
        $this->assertSame('Accident', $status->getName());
        $this->assertSame(993, $status->getCount());
    }

    public function testDeserializeLapTimes()
    {
        $httpResponse = $this->createHttpResponseFromFile('lap_times.xml', 'application/xml; charset=utf-8');
        $ergastResponse = $this->deserializeHttpResponse($httpResponse);

        $this->assertInstanceOf('BrieucThomas\ErgastClient\Model\Response', $ergastResponse);
        $this->assertSame('http://ergast.com/api/f1/2011/5/laps', $ergastResponse->getUrl());
        $this->assertSame('f1', $ergastResponse->getSeries());
        $this->assertSame(2, $ergastResponse->getTotal());
        $this->assertSame(30, $ergastResponse->getLimit());
        $this->assertSame(0, $ergastResponse->getOffset());
        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $ergastResponse->getRaces());
        $this->assertCount(1, $ergastResponse->getRaces());

        $race = $ergastResponse->getRaces()->first();
        $this->assertInstanceOf('BrieucThomas\ErgastClient\Model\Race', $race);
        $this->assertCount(2, $race->getLaps());

        $lap = $race->getLaps()->first();
        $this->assertInstanceOf('BrieucThomas\ErgastClient\Model\Lap', $lap);
        $this->assertSame(1, $lap->getNumber());
        $this->assertCount(2, $lap->getTiming());

        $timing = $lap->getTiming()->first();
        $this->assertInstanceOf('BrieucThomas\ErgastClient\Model\Timing', $timing);
        $this->assertSame(1, $timing->getLap());
        $this->assertSame(1, $timing->getPosition());
        $this->assertSame('alonso', $timing->getDriverId());
        $this->assertSame('1:34.494', $timing->getTime());

        $timing = $lap->getTiming()->next();
        $this->assertInstanceOf('BrieucThomas\ErgastClient\Model\Timing', $timing);
        $this->assertSame(1, $timing->getLap());
        $this->assertSame(2, $timing->getPosition());
        $this->assertSame('vettel', $timing->getDriverId());
        $this->assertSame('1:35.274', $timing->getTime());

        $lap = $race->getLaps()->next();
        $this->assertInstanceOf('BrieucThomas\ErgastClient\Model\Lap', $lap);
        $this->assertSame(2, $lap->getNumber());
        $this->assertCount(2, $lap->getTiming());

        $timing = $lap->getTiming()->first();
        $this->assertInstanceOf('BrieucThomas\ErgastClient\Model\Timing', $timing);
        $this->assertSame(2, $timing->getLap());
        $this->assertSame(1, $timing->getPosition());
        $this->assertSame('alonso', $timing->getDriverId());
        $this->assertSame('1:30.812', $timing->getTime());

        $timing = $lap->getTiming()->next();
        $this->assertInstanceOf('BrieucThomas\ErgastClient\Model\Timing', $timing);
        $this->assertSame(2, $timing->getLap());
        $this->assertSame(2, $timing->getPosition());
        $this->assertSame('vettel', $timing->getDriverId());
        $this->assertSame('1:30.633', $timing->getTime());
    }

    public function testDeserializePitStops()
    {
        $httpResponse = $this->createHttpResponseFromFile('pit_stops.xml', 'application/xml; charset=utf-8');
        $ergastResponse = $this->deserializeHttpResponse($httpResponse);

        $this->assertInstanceOf('BrieucThomas\ErgastClient\Model\Response', $ergastResponse);
        $this->assertSame('http://ergast.com/api/f1/2011/5/pitstops', $ergastResponse->getUrl());
        $this->assertSame('f1', $ergastResponse->getSeries());
        $this->assertSame(2, $ergastResponse->getTotal());
        $this->assertSame(30, $ergastResponse->getLimit());
        $this->assertSame(0, $ergastResponse->getOffset());
        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $ergastResponse->getRaces());
        $this->assertCount(1, $ergastResponse->getRaces());

        $race = $ergastResponse->getRaces()->first();
        $this->assertCount(2, $race->getPitStops());

        $pitStop = $race->getPitStops()->first();

        $this->assertSame('kobayashi', $pitStop->getDriverId());
        $this->assertSame(24.871, $pitStop->getDuration());
        $this->assertSame('14:05:11', $pitStop->getTime()->format('H:i:s'));
        $this->assertSame(1, $pitStop->getStop());
        $this->assertSame(1, $pitStop->getLap());

        $pitStop = $race->getPitStops()->next();

        $this->assertSame('perez', $pitStop->getDriverId());
        $this->assertSame(23.592, $pitStop->getDuration());
        $this->assertSame('14:14:14', $pitStop->getTime()->format('H:i:s'));
        $this->assertSame(1, $pitStop->getStop());
        $this->assertSame(7, $pitStop->getLap());
    }

    private function createHttpResponseFromFile(string $fixture, string $contentType) : HttpResponse
    {
        $body = file_get_contents($this->getFixtureDir() . '/' . $fixture);

        return $this->createHttpResponse($body, $contentType);
    }

    private function createHttpResponse(string $body, string $contentType, $status = 200) : HttpResponse
    {
        return new HttpResponse($status, ['Content-Type' => $contentType], $body);
    }

    private function deserializeHttpResponse(HttpResponse $httpResponse) : ErgastResponse
    {
        $httpClient = $this->createHttpClient($httpResponse);
        $serializer = SerializerBuilder::create()
            ->addMetadataDir($this->getRootDir() . '/src/BrieucThomas/ErgastClient/config/serializer/')
            ->build()
        ;
        $ergastClient = new ErgastClient($httpClient, $serializer);
        $httpRequest = $this->createHttpRequest();

        return $ergastClient->execute($httpRequest);
    }

    private function createHttpClient(ResponseInterface $httpResponse) : ClientInterface
    {
        $httpClient = $this
            ->getMockBuilder('GuzzleHttp\ClientInterface')
            ->getMock()
        ;

        $httpClient
            ->method('send')
            ->willReturn($httpResponse)
        ;

        return $httpClient;
    }

    private function createHttpRequest() : RequestInterface
    {
        return $this
            ->getMockBuilder('Psr\Http\Message\RequestInterface')
            ->getMock()
        ;
    }

    private function getRootDir() : string
    {
        return __DIR__ . '/../../..';
    }

    private function getFixtureDir() : string
    {
        return __DIR__ . '/data';
    }
}
