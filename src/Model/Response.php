<?php

/*
 * (c) Brieuc Thomas <tbrieuc@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ergast\Model;

/**
 * @author Brieuc Thomas <tbrieuc@gmail.com>
 */
class Response implements ResponseInterface
{
    private $series;
    private $url;
    private $limit;
    private $offset;
    private $total;
    private $driverTable;
    private $circuitTable;
    private $constructorTable;
    private $seasonTable;
    private $raceTable;
    private $standingsTable;
    private $statusTable;

    public function getSeries(): string
    {
        return $this->series;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function getDriverTable(): DriverTable
    {
        return $this->driverTable;
    }

    public function getCircuitTable(): ?CircuitTable
    {
        return $this->circuitTable;
    }

    public function getConstructorTable(): ?ConstructorTable
    {
        return $this->constructorTable;
    }

    public function getSeasonTable(): ?SeasonTable
    {
        return $this->seasonTable;
    }

    public function getRaceTable(): ?RaceTable
    {
        return $this->raceTable;
    }

    public function getStandingsTable(): StandingsTable
    {
        return $this->standingsTable;
    }

    public function getStatusTable(): StatusTable
    {
        return $this->statusTable;
    }
}
