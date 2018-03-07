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
interface ResponseInterface
{
    public function getSeries(): string;

    public function getUrl(): string;

    public function getLimit(): int;

    public function getOffset(): int;

    public function getTotal(): int;
}
