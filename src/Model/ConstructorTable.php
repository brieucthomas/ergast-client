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
class ConstructorTable implements \IteratorAggregate, \Countable
{
    private $constructors;

    public function all(): array
    {
        return $this->constructors;
    }

    public function first(): ?Constructor
    {
        return $this->constructors[0] ?? null;
    }

    public function find($id): ?Constructor
    {
        foreach ($this->constructors as $constructor) {
            if ($id === $constructor->getId()) {
                return $constructor;
            }
        }

        return null;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->constructors);
    }

    public function count(): int
    {
        return \count($this->constructors);
    }
}
