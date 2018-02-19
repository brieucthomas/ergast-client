<?php

/*
 * (c) Brieuc Thomas <tbrieuc@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BrieucThomas\ErgastClient\Request;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\UriTemplate;
use Psr\Http\Message\RequestInterface;

/**
 * @author Brieuc Thomas <tbrieuc@gmail.com>
 */
class RequestBuilder
{
    protected $schema = 'http';
    protected $host = 'www.ergast.com';
    protected $format = 'xml';
    protected $select;
    protected $id;
    protected $series = 'f1';
    protected $season;
    protected $round;
    protected $criteria = [];
    protected $limit = 1000;
    protected $offset = 0;

    public function getSchema(): string
    {
        return $this->schema;
    }

    public function setSchema(string $schema): self
    {
        $this->schema = $schema;

        return $this;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function setHost(string $host): self
    {
        $this->host = $host;

        return $this;
    }

    public function getFormat(): string
    {
        return $this->format;
    }

    public function setFormat(string $format): self
    {
        $this->format = $format;

        return $this;
    }

    public function findSeasons(): self
    {
        $this->select = 'seasons';

        return $this;
    }

    public function findCircuits(): self
    {
        $this->select = 'circuits';

        return $this;
    }

    public function findRaces(): self
    {
        $this->select = 'races';

        return $this;
    }

    public function findConstructors(): self
    {
        $this->select = 'constructors';

        return $this;
    }

    public function findDrivers(): self
    {
        $this->select = 'drivers';

        return $this;
    }

    public function findQualifying(): self
    {
        $this->select = 'qualifying';

        return $this;
    }

    public function findResults(): self
    {
        $this->select = 'results';

        return $this;
    }

    public function findDriverStandings(): self
    {
        $this->select = 'driverStandings';

        return $this;
    }

    public function findConstructorStandings(): self
    {
        $this->select = 'constructorStandings';

        return $this;
    }

    public function findFinishingStatus(): self
    {
        $this->select = 'status';

        return $this;
    }

    public function findLapTimes(): self
    {
        $this->select = 'laps';

        return $this;
    }

    public function findPitStops(): self
    {
        $this->select = 'pitstops';

        return $this;
    }

    public function bySeries(string $series): self
    {
        $this->series = $series;

        return $this;
    }

    public function bySeason(int $season): self
    {
        $this->season = $season;

        return $this;
    }

    public function byCurrentSeason(): self
    {
        $this->season = 'current';

        return $this;
    }

    public function byRound(int $round): self
    {
        $this->round = $round;

        return $this;
    }

    public function byLastRound(): self
    {
        $this->round = 'last';

        return $this;
    }

    public function byId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function byCircuit(string $id): self
    {
        $this->criteria['circuits'] = $id;

        return $this;
    }

    public function byConstructor(string $id): self
    {
        $this->criteria['constructors'] = $id;

        return $this;
    }

    public function byDriver(string $id): self
    {
        $this->criteria['drivers'] = $id;

        return $this;
    }

    public function byDriverStandings(int $rank): self
    {
        $this->criteria['driverStandings'] = $rank;

        return $this;
    }

    public function byConstructorStandings(int $rank): self
    {
        $this->criteria['constructorStandings'] = $rank;

        return $this;
    }

    public function byFinishingPosition(int $finishingPosition): self
    {
        $this->id = $finishingPosition;

        return $this;
    }

    public function byWinner(): self
    {
        $this->byFinishingPosition(1);

        return $this;
    }

    public function byGrid(int $position): self
    {
        $this->criteria['grid'] = $position;

        return $this;
    }

    public function byResult(int $position): self
    {
        $this->criteria['results'] = $position;

        return $this;
    }

    public function byFinishingStatus(string $status): self
    {
        if ('status' === $this->select) {
            $this->id = $status;
        } else {
            $this->criteria['status'] = $status;
        }

        return $this;
    }

    public function byFastestLap(int $rank = 1): self
    {
        $this->criteria['fastest'] = $rank;

        return $this;
    }

    public function byLapNumber(int $lapNumber): self
    {
        if ('laps' === $this->select) {
            $this->id = $lapNumber;
        } else {
            $this->criteria['laps'] = $lapNumber;
        }

        return $this;
    }

    public function byStopNumber(int $stopNumber): self
    {
        $this->id = $stopNumber;

        return $this;
    }

    public function getMaxResults(): int
    {
        return $this->limit;
    }

    public function setMaxResults(int $maxResults): self
    {
        $this->limit = $maxResults;

        return $this;
    }

    public function getFirstResult()
    {
        return $this->offset;
    }

    public function setFirstResult($firstResult): self
    {
        $this->offset = $firstResult;

        return $this;
    }

    public function build(): RequestInterface
    {
        $uriTemple = new UriTemplate();

        $criteria = [];

        foreach ($this->criteria as $key => $value) {
            $criteria[] = $key;
            $criteria[] = $value;
        }

        $uri = $uriTemple->expand('{schema}://{host}/api{/series}{/season}{/round}{/criteria*}/{select}{/id}{.format}{?limit,offset}', [
            'schema' => $this->schema,
            'host' => $this->host,
            'series' => $this->series,
            'season' => $this->season,
            'round' => $this->round,
            'criteria' => $criteria,
            'select' => $this->select,
            'id' => $this->id,
            'format' => empty($this->select) ? null : $this->format,
            'limit' => $this->limit,
            'offset' => $this->offset,
        ]);

        return new Request('Get', $uri);
    }
}
