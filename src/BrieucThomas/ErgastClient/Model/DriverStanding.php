<?php

/*
 * (c) Brieuc Thomas <tbrieuc@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BrieucThomas\ErgastClient\Model;

/**
 * @author Brieuc Thomas <brieuc.thomas@orange.com>
 */
class DriverStanding extends AbstractStanding
{
    private $driver;
    private $constructor;

    public function getDriver(): Driver
    {
        return $this->driver;
    }

    public function getConstructor(): Constructor
    {
        return $this->constructor;
    }
}
