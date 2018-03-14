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
 * @author Brieuc Thomas <brieuc.thomas@orange.com>
 */
class Standings
{
    private $season;
    private $round;
    private $driverStandings;
    private $constructorStandings;

    public function getSeason(): int
    {
        return $this->season;
    }

    public function getRound(): int
    {
        return $this->round;
    }

    /**
     * @return Collection|DriverStanding[]
     */
    public function getDriverStandings(): Collection
    {
        return $this->driverStandings;
    }

    /**
     * @return Collection|ConstructorStanding[]
     */
    public function getConstructorStandings(): Collection
    {
        return $this->constructorStandings;
    }
}
