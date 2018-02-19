<?php

/*
 * (c) Brieuc Thomas <tbrieuc@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BrieucThomas\ErgastClient\Model;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @author Brieuc Thomas <tbrieuc@gmail.com>
 */
class Response
{
    private $series;
    private $url;
    private $limit;
    private $offset;
    private $total;
    private $seasons;
    private $races;
    private $standings;
    private $circuits;
    private $drivers;
    private $constructors;
    private $finishingStatues;

    public function getSeries(): string
    {
        return $this->series;
    }

    /**
     * Returns the request url.
     *
     * @return string
     */
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

    public function getSeasons(): ArrayCollection
    {
        return $this->seasons;
    }

    public function getRaces(): ArrayCollection
    {
        return $this->races;
    }

    public function getStandings(): ArrayCollection
    {
        return $this->standings;
    }

    public function getCircuits(): ArrayCollection
    {
        return $this->circuits;
    }

    public function getDrivers(): ArrayCollection
    {
        return $this->drivers;
    }

    public function getConstructors(): ArrayCollection
    {
        return $this->constructors;
    }

    public function getFinishingStatues(): ArrayCollection
    {
        return $this->finishingStatues;
    }
}
