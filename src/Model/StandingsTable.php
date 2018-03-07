<?php

/*
 * (c) Brieuc Thomas <tbrieuc@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ergast\Model;

/**
 * @author Brieuc Thomas <brieuc.thomas@orange.com>
 */
class StandingsTable implements \IteratorAggregate, \Countable
{
    private $standingsList;

    public function all(): array
    {
        return $this->standingsList;
    }

    public function first(): ?StandingsList
    {
        return $this->standingsList[0] ?? null;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->standingsList);
    }

    public function count(): int
    {
        return \count($this->standingsList);
    }
}
