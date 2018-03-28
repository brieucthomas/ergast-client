<?php

/*
 * (c) Brieuc Thomas <tbrieuc@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ergast\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @author Brieuc Thomas <tbrieuc@gmail.com>
 */
class Race
{
    private $season;
    private $round;
    private $name;
    private $circuit;
    private $date;
    private $time;
    private $url;
    private $qualifying;
    private $results;
    private $laps;
    private $pitStops;

    public function __construct()
    {
        $this->qualifying = new ArrayCollection();
        $this->results = new ArrayCollection();
        $this->laps = new ArrayCollection();
        $this->pitStops = new ArrayCollection();
    }

    public function getSeason(): int
    {
        return $this->season;
    }

    public function getRound(): int
    {
        return $this->round;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCircuit(): Circuit
    {
        return $this->circuit;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function getTime(): ?\DateTime
    {
        return $this->time;
    }

    public function getStartDate(): \DateTime
    {
        $startDate = clone $this->getDate();
        $time = $this->getTime();

        if ($time instanceof \DateTime) {
            $startDate->setTime($time->format('H'), $time->format('i'), $time->format('s'));
        }

        return $startDate;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return Collection|Qualifying[]
     */
    public function getQualifying(): Collection
    {
        return $this->qualifying;
    }

    /**
     * @return Collection|Result[]
     */
    public function getResults(): Collection
    {
        return $this->results;
    }

    /**
     * @return Collection|Lap[]
     */
    public function getLaps(): Collection
    {
        return $this->laps;
    }

    /**
     * @return Collection|PitStop[]
     */
    public function getPitStops(): Collection
    {
        return $this->pitStops;
    }
}
