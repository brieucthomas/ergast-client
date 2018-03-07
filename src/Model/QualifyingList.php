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
class QualifyingList implements \Countable, \IteratorAggregate
{
    private $qualifying;

    public function all(): array
    {
        return $this->qualifying;
    }

    public function first(): ?Qualifying
    {
        return $this->qualifying[0] ?? null;
    }

    public function count(): int
    {
        return \count($this->qualifying);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->qualifying);
    }
}
