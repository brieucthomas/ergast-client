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
class RaceTable implements \Countable, \IteratorAggregate
{
    private $races;

    public function all(): array
    {
        return $this->races;
    }

    public function first(): ?Race
    {
        return $this->races[0] ?? null;
    }

    public function count(): int
    {
        return \count($this->races);
    }

    /**
     * @return Race[]
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->races);
    }
}
