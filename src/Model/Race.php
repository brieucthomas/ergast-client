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
class Race
{
    private $season;
    private $round;
    private $name;
    private $circuit;
    private $date;
    private $time;
    private $url;
    private $qualifyingList;
    private $resultList;
    private $lapsList;
    private $pitStopsList;

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

    public function getQualifyingList(): QualifyingList
    {
        return $this->qualifyingList;
    }

    public function getResultList(): ResultsList
    {
        return $this->resultList;
    }

    public function getLapsList(): LapsList
    {
        return $this->lapsList;
    }

    public function getPitStopsList(): PitStopsList
    {
        return $this->pitStopsList;
    }
}
