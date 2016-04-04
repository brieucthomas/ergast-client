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
class Time
{
    private $value;
    private $millis;

    public function getValue() : string
    {
        return $this->value;
    }

    public function getMillis() : int
    {
        return $this->millis;
    }
}
