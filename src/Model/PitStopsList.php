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
class PitStopsList implements \Countable, \IteratorAggregate
{
    private $pitStops;

    public function all(): array
    {
        return $this->pitStops;
    }

    public function first(): ?PitStop
    {
        return $this->pitStops[0] ?? null;
    }

    public function count(): int
    {
        return \count($this->pitStops);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->pitStops);
    }
}
