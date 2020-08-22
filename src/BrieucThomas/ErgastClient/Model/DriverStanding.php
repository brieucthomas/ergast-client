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
class DriverStanding extends AbstractStanding
{
    private $driver;
    private $constructors;

    public function getDriver(): Driver
    {
        return $this->driver;
    }

    public function getConstructors(): ArrayCollection
    {
        return $this->constructors;
    }
}
