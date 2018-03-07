<?php

/*
 * (c) Brieuc Thomas <tbrieuc@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ergast\Exception;

/**
 * @author Brieuc Thomas <tbrieuc@gmail.com>
 */
class UnsupportedSeriesException extends \InvalidArgumentException implements ExceptionInterface
{
    public function __construct(string $name)
    {
        parent::__construct(sprintf('Supported series are f1 or fe, %s given.', $name), 404);
    }
}
