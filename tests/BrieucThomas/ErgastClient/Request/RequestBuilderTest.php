<?php

/*
 * (c) Brieuc Thomas <tbrieuc@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\BrieucThomas\ErgastClient\Request;

use BrieucThomas\ErgastClient\Request\RequestBuilder;

class RequestBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testGetDefaultUri()
    {
        $requestBuilder = new RequestBuilder();
        $request = $requestBuilder->build();

        $this->assertSame('http', $requestBuilder->getSchema());
        $this->assertSame('www.ergast.com', $requestBuilder->getHost());
        $this->assertSame('xml', $requestBuilder->getFormat());
        $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $request);
        $this->assertSame('http://www.ergast.com/api/f1/?limit=1000&offset=0', (string) $request->getUri());
    }

    public function testChangeHost()
    {
        $requestBuilder = new RequestBuilder();
        $requestBuilder->setHost('www.example.com');
        $request = $requestBuilder->build();

        $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $request);
        $this->assertSame('http://www.example.com/api/f1/?limit=1000&offset=0', (string) $request->getUri());
    }

    public function testChangeSchema()
    {
        $requestBuilder = new RequestBuilder();
        $requestBuilder->setSchema('https');
        $request = $requestBuilder->build();

        $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $request);
        $this->assertSame('https://www.ergast.com/api/f1/?limit=1000&offset=0', (string) $request->getUri());
    }

    public function testChangeFormat()
    {
        $requestBuilder = new RequestBuilder();
        $requestBuilder
            ->findSeasons()
            ->setFormat('json')
        ;
        $request = $requestBuilder->build();

        $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $request);
        $this->assertSame('http://www.ergast.com/api/f1/seasons.json?limit=1000&offset=0', (string) $request->getUri());
    }

    public function testGetLimitAndOffset()
    {
        $requestBuilder = new RequestBuilder();
        $request = $requestBuilder->build();

        $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $request);
        $this->assertSame(0, $requestBuilder->getFirstResult());
        $this->assertSame(1000, $requestBuilder->getMaxResults());
    }

    public function testChangeLimitAndOffset()
    {
        $requestBuilder = new RequestBuilder();
        $requestBuilder
            ->setFirstResult(2)
            ->setMaxResults(5)
        ;
        $request = $requestBuilder->build();

        $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $request);
        $this->assertSame('http://www.ergast.com/api/f1/?limit=5&offset=2', (string) $request->getUri());
    }

    public function testChangeSeries()
    {
        $requestBuilder = new RequestBuilder();
        $requestBuilder->bySeries('fe');
        $request = $requestBuilder->build();

        $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $request);
        $this->assertSame('http://www.ergast.com/api/fe/?limit=1000&offset=0', (string) $request->getUri());
    }

    public function testBuildUriToFindSeasons()
    {
        $requestBuilder = new RequestBuilder();
        $requestBuilder->findSeasons();
        $request = $requestBuilder->build();

        $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $request);
        $this->assertSame('http://www.ergast.com/api/f1/seasons.xml?limit=1000&offset=0', (string) $request->getUri());
    }

    public function testBuildUriToFindSeasonsByDriverAndConstructor()
    {
        $requestBuilder = new RequestBuilder();
        $requestBuilder
            ->findSeasons()
            ->byDriver('alonso')
            ->byConstructor('renault')
        ;
        $request = $requestBuilder->build();

        $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $request);
        $this->assertSame('http://www.ergast.com/api/f1/drivers/alonso/constructors/renault/seasons.xml?limit=1000&offset=0', (string) $request->getUri());
    }

    public function testBuildUriToFindSeasonsByDriverAndByItsStandings()
    {
        $requestBuilder = new RequestBuilder();
        $requestBuilder
            ->findSeasons()
            ->byDriver('alonso')
            ->byDriverStandings(1)
        ;
        $request = $requestBuilder->build();

        $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $request);
        $this->assertSame('http://www.ergast.com/api/f1/drivers/alonso/driverStandings/1/seasons.xml?limit=1000&offset=0', (string) $request->getUri());
    }

    public function testBuildUriToFindSeasonsByConstructorAndByItsStandings()
    {
        $requestBuilder = new RequestBuilder();
        $requestBuilder
            ->findSeasons()
            ->byConstructor('renault')
            ->byConstructorStandings(1)
        ;
        $request = $requestBuilder->build();

        $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $request);
        $this->assertSame('http://www.ergast.com/api/f1/constructors/renault/constructorStandings/1/seasons.xml?limit=1000&offset=0', (string) $request->getUri());
    }

    public function testBuildUriToFindRacesBySeason()
    {
        $requestBuilder = new RequestBuilder();
        $requestBuilder
            ->findRaces()
            ->bySeason(2012)
        ;
        $request = $requestBuilder->build();

        $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $request);
        $this->assertSame('http://www.ergast.com/api/f1/2012/races.xml?limit=1000&offset=0', (string) $request->getUri());
    }

    public function testBuildUriToFindRacesByCurrentSeason()
    {
        $requestBuilder = new RequestBuilder();
        $requestBuilder
            ->findRaces()
            ->byCurrentSeason()
        ;
        $request = $requestBuilder->build();

        $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $request);
        $this->assertSame('http://www.ergast.com/api/f1/current/races.xml?limit=1000&offset=0', (string) $request->getUri());
    }

    public function testBuildUriToFindRacesBySeasonAndRound()
    {
        $requestBuilder = new RequestBuilder();
        $requestBuilder
            ->findRaces()
            ->bySeason(2012)
            ->byRound(5)
        ;
        $request = $requestBuilder->build();

        $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $request);
        $this->assertSame('http://www.ergast.com/api/f1/2012/5/races.xml?limit=1000&offset=0', (string) $request->getUri());
    }

    public function testBuildUriToFindRacesByDriverAndCircuit()
    {
        $requestBuilder = new RequestBuilder();
        $requestBuilder
            ->findRaces()
            ->byDriver('alonso')
            ->byCircuit('monza')
        ;
        $request = $requestBuilder->build();

        $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $request);
        $this->assertSame('http://www.ergast.com/api/f1/drivers/alonso/circuits/monza/races.xml?limit=1000&offset=0', (string) $request->getUri());
    }

    public function testBuildUriToFindResultsBySeasonAndRound()
    {
        $requestBuilder = new RequestBuilder();
        $requestBuilder
            ->findResults()
            ->bySeason(2008)
            ->byRound(5)
        ;
        $request = $requestBuilder->build();

        $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $request);
        $this->assertSame('http://www.ergast.com/api/f1/2008/5/results.xml?limit=1000&offset=0', (string) $request->getUri());
    }

    public function testBuildUriToFindResultsByFinishingStatus()
    {
        $requestBuilder = new RequestBuilder();
        $requestBuilder
            ->findResults()
            ->bySeason(2008)
            ->byRound(5)
            ->byFinishingStatus(1)
        ;
        $request = $requestBuilder->build();

        $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $request);
        $this->assertSame('http://www.ergast.com/api/f1/2008/5/status/1/results.xml?limit=1000&offset=0', (string) $request->getUri());
    }

    public function testBuildUriToFindLastResults()
    {
        $requestBuilder = new RequestBuilder();
        $requestBuilder
            ->findResults()
            ->byCurrentSeason()
            ->byLastRound()
        ;
        $request = $requestBuilder->build();

        $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $request);
        $this->assertSame('http://www.ergast.com/api/f1/current/last/results.xml?limit=1000&offset=0', (string) $request->getUri());
    }

    public function testBuildUriToFindResultsBySeasonAndDriver()
    {
        $requestBuilder = new RequestBuilder();
        $requestBuilder
            ->findResults()
            ->bySeason(2008)
            ->byDriver('alonso')
        ;
        $request = $requestBuilder->build();

        $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $request);
        $this->assertSame('http://www.ergast.com/api/f1/2008/drivers/alonso/results.xml?limit=1000&offset=0', (string) $request->getUri());
    }

    public function testBuildUriToFindResultsByDriverAndConstructor()
    {
        $requestBuilder = new RequestBuilder();
        $requestBuilder
            ->findResults()
            ->byDriver('alonso')
            ->byConstructor('renault')
        ;
        $request = $requestBuilder->build();

        $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $request);
        $this->assertSame('http://www.ergast.com/api/f1/drivers/alonso/constructors/renault/results.xml?limit=1000&offset=0', (string) $request->getUri());
    }

    public function testBuildUriToFindResultsBySeasonAndDriverAndFinishingPosition()
    {
        $requestBuilder = new RequestBuilder();
        $requestBuilder
            ->findResults()
            ->bySeason(2008)
            ->byDriver('alonso')
            ->byFinishingPosition(2)
        ;
        $request = $requestBuilder->build();

        $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $request);
        $this->assertSame('http://www.ergast.com/api/f1/2008/drivers/alonso/results/2.xml?limit=1000&offset=0', (string) $request->getUri());
    }

    public function testBuildUriToFindRaceWinnersBySeason()
    {
        $requestBuilder = new RequestBuilder();
        $requestBuilder
            ->findResults()
            ->bySeason(2008)
            ->byWinner()
        ;
        $request = $requestBuilder->build();

        $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $request);
        $this->assertSame('http://www.ergast.com/api/f1/2008/results/1.xml?limit=1000&offset=0', (string) $request->getUri());
    }

    public function testBuildUriToFindResultsForTheDriverWhiAchievesTheFastestLapInRace()
    {
        $requestBuilder = new RequestBuilder();
        $requestBuilder
            ->findResults()
            ->bySeason(2010)
            ->byRound(1)
            ->byFastestLap()
        ;
        $request = $requestBuilder->build();

        $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $request);
        $this->assertSame('http://www.ergast.com/api/f1/2010/1/fastest/1/results.xml?limit=1000&offset=0', (string) $request->getUri());
    }

    public function testBuildUriToFindQualifyingBySeasonAndRound()
    {
        $requestBuilder = new RequestBuilder();
        $requestBuilder
            ->findQualifying()
            ->bySeason(2008)
            ->byRound(5)
        ;
        $request = $requestBuilder->build();

        $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $request);
        $this->assertSame('http://www.ergast.com/api/f1/2008/5/qualifying.xml?limit=1000&offset=0', (string) $request->getUri());
    }

    public function testBuildUriToFindQualifyingByDriverAndConstructor()
    {
        $requestBuilder = new RequestBuilder();
        $requestBuilder
            ->findQualifying()
            ->byDriver('alonso')
            ->byConstructor('renault')
        ;
        $request = $requestBuilder->build();

        $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $request);
        $this->assertSame('http://www.ergast.com/api/f1/drivers/alonso/constructors/renault/qualifying.xml?limit=1000&offset=0', (string) $request->getUri());
    }

    public function testBuildUriToFindAllSeasonPoleMan()
    {
        $requestBuilder = new RequestBuilder();
        $requestBuilder
            ->findQualifying()
            ->bySeason(2008)
            ->byGrid(1)
        ;
        $request = $requestBuilder->build();

        $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $request);
        $this->assertSame('http://www.ergast.com/api/f1/2008/grid/1/qualifying.xml?limit=1000&offset=0', (string) $request->getUri());
    }

    public function testBuildUriToFindQualifyingOfAllRaceWinner()
    {
        $requestBuilder = new RequestBuilder();
        $requestBuilder
            ->findQualifying()
            ->bySeason(2008)
            ->byResult(1)
        ;
        $request = $requestBuilder->build();

        $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $request);
        $this->assertSame('http://www.ergast.com/api/f1/2008/results/1/qualifying.xml?limit=1000&offset=0', (string) $request->getUri());
    }

    public function testBuildUriToFindDriverStandingsBySeasonAndRound()
    {
        $requestBuilder = new RequestBuilder();
        $requestBuilder
            ->findDriverStandings()
            ->bySeason(2008)
            ->byRound(5)
        ;
        $request = $requestBuilder->build();

        $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $request);
        $this->assertSame('http://www.ergast.com/api/f1/2008/5/driverStandings.xml?limit=1000&offset=0', (string) $request->getUri());
    }

    public function testBuildUriToFindDriverStandingsBySeason()
    {
        $requestBuilder = new RequestBuilder();
        $requestBuilder
            ->findDriverStandings()
            ->bySeason(2008)
        ;
        $request = $requestBuilder->build();

        $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $request);
        $this->assertSame('http://www.ergast.com/api/f1/2008/driverStandings.xml?limit=1000&offset=0', (string) $request->getUri());
    }

    public function testBuildUriToFindDriverStandingsByCurrentSeason()
    {
        $requestBuilder = new RequestBuilder();
        $requestBuilder
            ->findDriverStandings()
            ->byCurrentSeason()
        ;
        $request = $requestBuilder->build();

        $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $request);
        $this->assertSame('http://www.ergast.com/api/f1/current/driverStandings.xml?limit=1000&offset=0', (string) $request->getUri());
    }

    public function testBuildUriToFindWorldDriversChampions()
    {
        $requestBuilder = new RequestBuilder();
        $requestBuilder
            ->findDriverStandings()
            ->byWinner()
        ;
        $request = $requestBuilder->build();

        $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $request);
        $this->assertSame('http://www.ergast.com/api/f1/driverStandings/1.xml?limit=1000&offset=0', (string) $request->getUri());
    }

    public function testBuildUriToFindDriverStandingsByDriver()
    {
        $requestBuilder = new RequestBuilder();
        $requestBuilder
            ->findDriverStandings()
            ->byDriver('alonso')
        ;
        $request = $requestBuilder->build();

        $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $request);
        $this->assertSame('http://www.ergast.com/api/f1/drivers/alonso/driverStandings.xml?limit=1000&offset=0', (string) $request->getUri());
    }

    public function testBuildUriToFindConstructorStandingsBySeasonAndRound()
    {
        $requestBuilder = new RequestBuilder();
        $requestBuilder
            ->findConstructorStandings()
            ->bySeason(2008)
            ->byRound(5)
        ;
        $request = $requestBuilder->build();

        $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $request);
        $this->assertSame('http://www.ergast.com/api/f1/2008/5/constructorStandings.xml?limit=1000&offset=0', (string) $request->getUri());
    }

    public function testBuildUriToFindConstructorStandingsBySeason()
    {
        $requestBuilder = new RequestBuilder();
        $requestBuilder
            ->findConstructorStandings()
            ->bySeason(2008)
        ;
        $request = $requestBuilder->build();

        $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $request);
        $this->assertSame('http://www.ergast.com/api/f1/2008/constructorStandings.xml?limit=1000&offset=0', (string) $request->getUri());
    }

    public function testBuildUriToFindConstructorStandingsByCurrentSeason()
    {
        $requestBuilder = new RequestBuilder();
        $requestBuilder
            ->findConstructorStandings()
            ->byCurrentSeason()
        ;
        $request = $requestBuilder->build();

        $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $request);
        $this->assertSame('http://www.ergast.com/api/f1/current/constructorStandings.xml?limit=1000&offset=0', (string) $request->getUri());
    }

    public function testBuildUriToFindWorldConstructorsChampions()
    {
        $requestBuilder = new RequestBuilder();
        $requestBuilder
            ->findConstructorStandings()
            ->byWinner()
        ;
        $request = $requestBuilder->build();

        $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $request);
        $this->assertSame('http://www.ergast.com/api/f1/constructorStandings/1.xml?limit=1000&offset=0', (string) $request->getUri());
    }

    public function testBuildUriToFindConstructorStandingsByConstructor()
    {
        $requestBuilder = new RequestBuilder();
        $requestBuilder
            ->findConstructorStandings()
            ->byConstructor('alonso')
        ;
        $request = $requestBuilder->build();

        $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $request);
        $this->assertSame('http://www.ergast.com/api/f1/constructors/alonso/constructorStandings.xml?limit=1000&offset=0', (string) $request->getUri());
    }

    public function testBuildUriToFindDrivers()
    {
        $requestBuilder = new RequestBuilder();
        $requestBuilder->findDrivers();
        $request = $requestBuilder->build();

        $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $request);
        $this->assertSame('http://www.ergast.com/api/f1/drivers.xml?limit=1000&offset=0', (string) $request->getUri());
    }

    public function testBuildUriToFindDriversBySeason()
    {
        $requestBuilder = new RequestBuilder();
        $requestBuilder
            ->findDrivers()
            ->bySeason(2010)
        ;
        $request = $requestBuilder->build();

        $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $request);
        $this->assertSame('http://www.ergast.com/api/f1/2010/drivers.xml?limit=1000&offset=0', (string) $request->getUri());
    }

    public function testBuildUriToFindDriversBySeasonAndRound()
    {
        $requestBuilder = new RequestBuilder();
        $requestBuilder
            ->findDrivers()
            ->bySeason(2010)
            ->byRound(2)
        ;
        $request = $requestBuilder->build();

        $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $request);
        $this->assertSame('http://www.ergast.com/api/f1/2010/2/drivers.xml?limit=1000&offset=0', (string) $request->getUri());
    }

    public function testBuildUriToFindASpecificDriver()
    {
        $requestBuilder = new RequestBuilder();
        $requestBuilder
            ->findDrivers()
            ->byId('alonso')
        ;
        $request = $requestBuilder->build();

        $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $request);
        $this->assertSame('http://www.ergast.com/api/f1/drivers/alonso.xml?limit=1000&offset=0', (string) $request->getUri());
    }

    public function testBuildUriToFindDriversWhoHaveDrivenForASpecificConstructorAtAParticularCircuit()
    {
        $requestBuilder = new RequestBuilder();
        $requestBuilder
            ->findDrivers()
            ->byConstructor('mclaren')
            ->byCircuit('monza')
        ;
        $request = $requestBuilder->build();

        $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $request);
        $this->assertSame('http://www.ergast.com/api/f1/constructors/mclaren/circuits/monza/drivers.xml?limit=1000&offset=0', (string) $request->getUri());
    }

    public function testBuildUriToFindDriversWhoHaveAchievedAParticularFinalPositionInTheChampionship()
    {
        $requestBuilder = new RequestBuilder();
        $requestBuilder
            ->findDrivers()
            ->byDriverStandings(1)
        ;
        $request = $requestBuilder->build();

        $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $request);
        $this->assertSame('http://www.ergast.com/api/f1/driverStandings/1/drivers.xml?limit=1000&offset=0', (string) $request->getUri());
    }

    public function testBuildUriToFindConstructors()
    {
        $requestBuilder = new RequestBuilder();
        $requestBuilder->findConstructors();
        $request = $requestBuilder->build();

        $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $request);
        $this->assertSame('http://www.ergast.com/api/f1/constructors.xml?limit=1000&offset=0', (string) $request->getUri());
    }

    public function testBuildUriToFindConstructorsBySeason()
    {
        $requestBuilder = new RequestBuilder();
        $requestBuilder
            ->findConstructors()
            ->bySeason(2010)
        ;
        $request = $requestBuilder->build();

        $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $request);
        $this->assertSame('http://www.ergast.com/api/f1/2010/constructors.xml?limit=1000&offset=0', (string) $request->getUri());
    }

    public function testBuildUriToFindConstructorsBySeasonAndRound()
    {
        $requestBuilder = new RequestBuilder();
        $requestBuilder
            ->findConstructors()
            ->bySeason(2010)
            ->byRound(2)
        ;
        $request = $requestBuilder->build();

        $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $request);
        $this->assertSame('http://www.ergast.com/api/f1/2010/2/constructors.xml?limit=1000&offset=0', (string) $request->getUri());
    }

    public function testBuildUriToFindASpecificConstructor()
    {
        $requestBuilder = new RequestBuilder();
        $requestBuilder
            ->findConstructors()
            ->byId('alonso')
        ;
        $request = $requestBuilder->build();

        $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $request);
        $this->assertSame('http://www.ergast.com/api/f1/constructors/alonso.xml?limit=1000&offset=0', (string) $request->getUri());
    }

    public function testBuildUriToFindConstructorsASpecificDriverHasDrivenForAtAParticularCircuit()
    {
        $requestBuilder = new RequestBuilder();
        $requestBuilder
            ->findConstructors()
            ->byDriver('alonso')
            ->byCircuit('monza')
        ;
        $request = $requestBuilder->build();

        $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $request);
        $this->assertSame('http://www.ergast.com/api/f1/drivers/alonso/circuits/monza/constructors.xml?limit=1000&offset=0', (string) $request->getUri());
    }

    public function testBuildUriToFindConstructorsWhoHaveAchievedAParticularFinalPositionInTheChampionship()
    {
        $requestBuilder = new RequestBuilder();
        $requestBuilder
            ->findConstructors()
            ->byConstructorStandings(1)
        ;
        $request = $requestBuilder->build();

        $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $request);
        $this->assertSame('http://www.ergast.com/api/f1/constructorStandings/1/constructors.xml?limit=1000&offset=0', (string) $request->getUri());
    }

    public function testBuildUriToFindCircuits()
    {
        $requestBuilder = new RequestBuilder();
        $requestBuilder->findCircuits();
        $request = $requestBuilder->build();

        $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $request);
        $this->assertSame('http://www.ergast.com/api/f1/circuits.xml?limit=1000&offset=0', (string) $request->getUri());
    }

    public function testBuildUriToFindCircuitsBySeason()
    {
        $requestBuilder = new RequestBuilder();
        $requestBuilder
            ->findCircuits()
            ->bySeason(2010)
        ;
        $request = $requestBuilder->build();

        $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $request);
        $this->assertSame('http://www.ergast.com/api/f1/2010/circuits.xml?limit=1000&offset=0', (string) $request->getUri());
    }

    public function testBuildUriToFindCircuitsBySeasonAndRound()
    {
        $requestBuilder = new RequestBuilder();
        $requestBuilder
            ->findCircuits()
            ->bySeason(2010)
            ->byRound(2)
        ;
        $request = $requestBuilder->build();

        $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $request);
        $this->assertSame('http://www.ergast.com/api/f1/2010/2/circuits.xml?limit=1000&offset=0', (string) $request->getUri());
    }

    public function testBuildUriToFindAParticularCircuits()
    {
        $requestBuilder = new RequestBuilder();
        $requestBuilder
            ->findCircuits()
            ->byId('monza')
        ;
        $request = $requestBuilder->build();

        $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $request);
        $this->assertSame('http://www.ergast.com/api/f1/circuits/monza.xml?limit=1000&offset=0', (string) $request->getUri());
    }

    public function testBuildUriToFindAllCircuitsAtWhichASpecificDriverHasDrivenForAParticularConstructor()
    {
        $requestBuilder = new RequestBuilder();
        $requestBuilder
            ->findCircuits()
            ->byDriver('alonso')
            ->byConstructor('mclaren')
        ;
        $request = $requestBuilder->build();

        $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $request);
        $this->assertSame('http://www.ergast.com/api/f1/drivers/alonso/constructors/mclaren/circuits.xml?limit=1000&offset=0', (string) $request->getUri());
    }

    public function testBuildUriToFindFinishingStatus()
    {
        $requestBuilder = new RequestBuilder();
        $requestBuilder->findFinishingStatus();
        $request = $requestBuilder->build();

        $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $request);
        $this->assertSame('http://www.ergast.com/api/f1/status.xml?limit=1000&offset=0', (string) $request->getUri());
    }

    public function testBuildUriToFindFinishingStatusByItsIdentifier()
    {
        $requestBuilder = new RequestBuilder();
        $requestBuilder
            ->findFinishingStatus()
            ->byFinishingStatus(1)
        ;
        $request = $requestBuilder->build();

        $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $request);
        $this->assertSame('http://www.ergast.com/api/f1/status/1.xml?limit=1000&offset=0', (string) $request->getUri());
    }

    public function testBuildUriToFindFinishingStatusBySeason()
    {
        $requestBuilder = new RequestBuilder();
        $requestBuilder
            ->findFinishingStatus()
            ->bySeason(2010)
        ;
        $request = $requestBuilder->build();

        $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $request);
        $this->assertSame('http://www.ergast.com/api/f1/2010/status.xml?limit=1000&offset=0', (string) $request->getUri());
    }

    public function testBuildUriToFindFinishingStatusBySeasonAndRound()
    {
        $requestBuilder = new RequestBuilder();
        $requestBuilder
            ->findFinishingStatus()
            ->bySeason(2010)
            ->byRound(2)
        ;
        $request = $requestBuilder->build();

        $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $request);
        $this->assertSame('http://www.ergast.com/api/f1/2010/2/status.xml?limit=1000&offset=0', (string) $request->getUri());
    }

    public function testBuildUriToFindFinishingStatusByADriverInAParticularSeason()
    {
        $requestBuilder = new RequestBuilder();
        $requestBuilder
            ->findFinishingStatus()
            ->bySeason(2008)
            ->byDriver('alonso')
        ;
        $request = $requestBuilder->build();

        $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $request);
        $this->assertSame('http://www.ergast.com/api/f1/2008/drivers/alonso/status.xml?limit=1000&offset=0', (string) $request->getUri());
    }

    public function testBuildUriToFindARaceLapTimes()
    {
        $requestBuilder = new RequestBuilder();
        $requestBuilder
            ->findLapTimes()
            ->bySeason(2011)
            ->byRound(5)
        ;
        $request = $requestBuilder->build();

        $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $request);
        $this->assertSame('http://www.ergast.com/api/f1/2011/5/laps.xml?limit=1000&offset=0', (string) $request->getUri());
    }

    public function testBuildUriToFindTheFirstRaceLapTimes()
    {
        $requestBuilder = new RequestBuilder();
        $requestBuilder
            ->findLapTimes()
            ->bySeason(2011)
            ->byRound(5)
            ->byLapNumber(1)
        ;
        $request = $requestBuilder->build();

        $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $request);
        $this->assertSame('http://www.ergast.com/api/f1/2011/5/laps/1.xml?limit=1000&offset=0', (string) $request->getUri());
    }

    public function testBuildUriToFindLapTimingForASpecificDriverAndLap()
    {
        $requestBuilder = new RequestBuilder();
        $requestBuilder
            ->findLapTimes()
            ->bySeason(2011)
            ->byRound(5)
            ->byDriver('alonso')
            ->byLapNumber(1)
        ;
        $request = $requestBuilder->build();

        $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $request);
        $this->assertSame('http://www.ergast.com/api/f1/2011/5/drivers/alonso/laps/1.xml?limit=1000&offset=0', (string) $request->getUri());
    }

    public function testBuildUriToFindARacePitStops()
    {
        $requestBuilder = new RequestBuilder();
        $requestBuilder
            ->findPitStops()
            ->bySeason(2011)
            ->byRound(5)
        ;
        $request = $requestBuilder->build();

        $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $request);
        $this->assertSame('http://www.ergast.com/api/f1/2011/5/pitstops.xml?limit=1000&offset=0', (string) $request->getUri());
    }

    public function testBuildUriToFindAStopByItsNumber()
    {
        $requestBuilder = new RequestBuilder();
        $requestBuilder
            ->findPitStops()
            ->bySeason(2011)
            ->byRound(5)
            ->byStopNumber(1)
        ;
        $request = $requestBuilder->build();

        $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $request);
        $this->assertSame('http://www.ergast.com/api/f1/2011/5/pitstops/1.xml?limit=1000&offset=0', (string) $request->getUri());
    }

    public function testBuildUriToFindAStopByItsLap()
    {
        $requestBuilder = new RequestBuilder();
        $requestBuilder
            ->findPitStops()
            ->bySeason(2011)
            ->byRound(5)
            ->byLapNumber(10)
        ;
        $request = $requestBuilder->build();

        $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $request);
        $this->assertSame('http://www.ergast.com/api/f1/2011/5/laps/10/pitstops.xml?limit=1000&offset=0', (string) $request->getUri());
    }

    public function testBuildUriToFindAStopByDriver()
    {
        $requestBuilder = new RequestBuilder();
        $requestBuilder
            ->findPitStops()
            ->bySeason(2011)
            ->byRound(5)
            ->byDriver('alonso')
        ;
        $request = $requestBuilder->build();

        $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $request);
        $this->assertSame('http://www.ergast.com/api/f1/2011/5/drivers/alonso/pitstops.xml?limit=1000&offset=0', (string) $request->getUri());
    }
}
