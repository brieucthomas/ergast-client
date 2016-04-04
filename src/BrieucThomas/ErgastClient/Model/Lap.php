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
 * @author Brieuc Thomas <tbrieuc@gmail.com>
 */
class Lap
{
    private $number;
    private $timing;

    public function getNumber() : int
    {
        return $this->number;
    }

    public function getTiming() : ArrayCollection
    {
        return $this->timing;
    }
}
