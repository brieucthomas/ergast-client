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
        $startDate = clone $this->date;
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

    public function getQualifying(): Collection
    {
        return $this->qualifying;
    }

    public function getResults(): Collection
    {
        return $this->results;
    }

    public function getLaps(): Collection
    {
        return $this->laps;
    }

    public function getPitStops(): Collection
    {
        return $this->pitStops;
    }
}
