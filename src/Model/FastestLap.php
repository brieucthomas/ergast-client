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
class FastestLap
{
    private $rank;
    private $lap;
    private $time;
    private $averageSpeed;

    public function getRank(): int
    {
        return $this->rank;
    }

    public function getLap(): int
    {
        return $this->lap;
    }

    public function getTime(): string
    {
        return $this->time;
    }

    public function getAverageSpeed(): Speed
    {
        return $this->averageSpeed;
    }
}
