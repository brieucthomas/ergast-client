<?php

/*
 * (c) Brieuc Thomas <tbrieuc@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ergast\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @author Brieuc Thomas <brieuc.thomas@orange.com>
 */
class DriverStanding extends AbstractStanding
{
    private $driver;
    private $constructors;

    public function __construct()
    {
        $this->constructors = new ArrayCollection();
    }

    public function getDriver(): Driver
    {
        return $this->driver;
    }

    public function getConstructors(): Collection
    {
        return $this->constructors;
    }
}
