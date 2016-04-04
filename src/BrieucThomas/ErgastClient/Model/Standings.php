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
 * @author Brieuc Thomas <brieuc.thomas@orange.com>
 */
class Standings
{
    private $season;
    private $round;
    private $driverStandings;
    private $constructorStandings;

    public function getSeason() : int
    {
        return $this->season;
    }

    public function getRound() : int
    {
        return $this->round;
    }

    public function getDriverStandings() : ArrayCollection
    {
        return $this->driverStandings;
    }

    public function getConstructorStandings() : ArrayCollection
    {
        return $this->constructorStandings;
    }
}
