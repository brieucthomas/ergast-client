<?php

/*
 * (c) Brieuc Thomas <tbrieuc@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BrieucThomas\ErgastClient\Model;

/**
 * @author Brieuc Thomas <tbrieuc@gmail.com>
 */
class PitStop
{
    private $driverId;
    private $stop;
    private $lap;
    private $time;
    private $duration;

    public function getDriverId(): string
    {
        return $this->driverId;
    }

    public function getDuration(): float
    {
        return $this->duration;
    }

    public function getLap(): int
    {
        return $this->lap;
    }

    public function getStop(): int
    {
        return $this->stop;
    }

    public function getTime(): \DateTime
    {
        return $this->time;
    }
}
