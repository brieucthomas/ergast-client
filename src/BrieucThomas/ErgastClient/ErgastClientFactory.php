<?php

/*
 * (c) Brieuc Thomas <tbrieuc@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BrieucThomas\ErgastClient;

use GuzzleHttp\Client as HttpClient;
use JMS\Serializer\SerializerBuilder;

/**
 * The ergast client factory.
 *
 * @author Brieuc Thomas <tbrieuc@gmail.com>
 */
class ErgastClientFactory
{
    public static function createErgastClient(): ErgastClient
    {
        $httpClient = new HttpClient();
        $serializer = SerializerBuilder::create()
            ->addMetadataDir(__DIR__.'/config/serializer/')
            ->build()
        ;

        return new ErgastClient($httpClient, $serializer);
    }
}
