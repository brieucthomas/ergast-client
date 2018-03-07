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
class Season
{
    private $year;
    private $url;

    /**
     * Returns the season year on 4 digits.
     *
     * @return int
     */
    public function getYear(): int
    {
        return $this->year;
    }

    /**
     * Returns the season Wikipedia page url.
     *
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }
}
