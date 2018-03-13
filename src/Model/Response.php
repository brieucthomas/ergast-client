<?php

/*
 * (c) Brieuc Thomas <tbrieuc@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ergast\Model;

use Doctrine\Common\Collections\Collection;

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
    private $drivers;
    private $circuits;
    private $constructors;
    private $seasons;
    private $races;
    private $standings;
    private $status;

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

    public function getDrivers(): ?Collection
    {
        return $this->drivers;
    }

    public function getCircuits(): ?Collection
    {
        return $this->circuits;
    }

    public function getConstructors(): ?Collection
    {
        return $this->constructors;
    }

    public function getSeasons(): ?Collection
    {
        return $this->seasons;
    }

    public function getRaces(): ?Collection
    {
        return $this->races;
    }

    public function getStandings(): ?Collection
    {
        return $this->standings;
    }

    public function getStatus(): ?Collection
    {
        return $this->status;
    }
}
