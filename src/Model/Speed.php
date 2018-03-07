<?php

/*
 * (c) Brieuc Thomas <tbrieuc@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ergast\Model;

/**
 * @author Brieuc Thomas <brieuc.thomas@orange.com>
 */
class Speed
{
    private $value;
    private $units;

    public function getValue(): float
    {
        return $this->value;
    }

    public function getUnits(): string
    {
        return $this->units;
    }
}
