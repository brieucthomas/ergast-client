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
class Timing
{
    private $driverId;
    private $lap;
    private $position;
    private $time;

    /**
     * Returns the driver slug.
     *
     * @return string
     */
    public function getDriverId(): string
    {
        return $this->driverId;
    }

    public function getLap(): int
    {
        return $this->lap;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function getTime(): string
    {
        return $this->time;
    }
}
