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
class Lap
{
    private $number;
    private $timing;

    public function getNumber(): int
    {
        return $this->number;
    }

    public function getTiming(): array
    {
        return $this->timing;
    }
}
