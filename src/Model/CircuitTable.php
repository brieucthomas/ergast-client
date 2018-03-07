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
class CircuitTable implements \IteratorAggregate, \Countable
{
    private $circuits;

    public function all(): array
    {
        return $this->circuits;
    }

    public function first(): ?Circuit
    {
        return $this->circuits[0] ?? null;
    }

    public function find($id): ?Circuit
    {
        foreach ($this->circuits as $circuit) {
            if ($id === $circuit->getId()) {
                return $circuit;
            }
        }

        return null;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->circuits);
    }

    public function count(): int
    {
        return \count($this->circuits);
    }
}
