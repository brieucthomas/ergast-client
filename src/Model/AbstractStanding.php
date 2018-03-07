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
abstract class AbstractStanding
{
    private $position;
    private $positionText;
    private $points;
    private $wins;

    public function getPosition(): int
    {
        return $this->position;
    }

    public function getPositionText(): string
    {
        return $this->positionText;
    }

    public function getPoints(): float
    {
        return $this->points;
    }

    public function getWins(): int
    {
        return $this->wins;
    }
}
