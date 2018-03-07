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
class SeasonTable implements \IteratorAggregate, \Countable
{
    private $seasons;

    public function all(): array
    {
        return $this->seasons;
    }

    public function first(): ?Season
    {
        return $this->seasons[0] ?? null;
    }

    public function find($id): ?Season
    {
        foreach ($this->seasons as $season) {
            if ($id === $season->getYear()) {
                return $season;
            }
        }

        return null;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->seasons);
    }

    public function count(): int
    {
        return \count($this->seasons);
    }
}
