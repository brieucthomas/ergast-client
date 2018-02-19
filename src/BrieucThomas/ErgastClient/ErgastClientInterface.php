<?php

/*
 * (c) Brieuc Thomas <tbrieuc@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BrieucThomas\ErgastClient;

use BrieucThomas\ErgastClient\Model\Response;
use Psr\Http\Message\RequestInterface;

/**
 * @author Brieuc Thomas <tbrieuc@gmail.com>
 */
interface ErgastClientInterface
{
    public function execute(RequestInterface $request): Response;
}
