<?php

/*
 * (c) Brieuc Thomas <tbrieuc@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ergast;

use Ergast\Model\Response;

/**
 * @author Brieuc Thomas <tbrieuc@gmail.com>
 */
interface ClientInterface
{
    public function sendRequest(string $path): Response;
}
