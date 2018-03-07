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
class StatusTable implements \IteratorAggregate, \Countable
{
    private $statuses;

    public function all(): array
    {
        return $this->statuses;
    }

    public function first(): ?Season
    {
        return $this->statuses[0] ?? null;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->statuses);
    }

    public function count(): int
    {
        return \count($this->statuses);
    }
}
