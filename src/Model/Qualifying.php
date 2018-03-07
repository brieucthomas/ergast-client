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
class Qualifying
{
    private $driver;
    private $constructor;
    private $number;
    private $position;
    private $q1;
    private $q2;
    private $q3;

    public function getDriver(): Driver
    {
        return $this->driver;
    }

    public function getConstructor(): Constructor
    {
        return $this->constructor;
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function getQ1()
    {
        return $this->q1;
    }

    public function getQ2()
    {
        return $this->q2;
    }

    public function getQ3()
    {
        return $this->q3;
    }
}
