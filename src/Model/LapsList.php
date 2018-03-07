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
class LapsList implements \Countable, \IteratorAggregate
{
    private $laps;

    public function all(): array
    {
        return $this->laps;
    }

    public function first(): ?Lap
    {
        return $this->laps[0] ?? null;
    }

    public function count(): int
    {
        return \count($this->laps);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->laps);
    }
}
