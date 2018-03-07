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
class ConstructorStanding extends AbstractStanding
{
    private $constructor;

    public function getConstructor(): Constructor
    {
        return $this->constructor;
    }
}
