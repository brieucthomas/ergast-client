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
class ResultsList implements \Countable, \IteratorAggregate
{
    private $results;

    public function all(): array
    {
        return $this->results;
    }

    public function first(): ?Result
    {
        return $this->results[0] ?? null;
    }

    public function count(): int
    {
        return \count($this->results);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->results);
    }
}
