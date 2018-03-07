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
class DriverTable implements \IteratorAggregate, \Countable
{
    private $drivers;

    public function all(): array
    {
        return $this->drivers;
    }

    public function first(): ?Driver
    {
        return $this->drivers[0] ?? null;
    }

    public function find($id): ?Driver
    {
        foreach ($this->drivers as $driver) {
            if ($id === $driver->getId()) {
                return $driver;
            }
        }

        return null;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->drivers);
    }

    public function count(): int
    {
        return \count($this->drivers);
    }
}
