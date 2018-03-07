<?php

/*
 * (c) Brieuc Thomas <tbrieuc@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ergast;

use Ergast\HttpClient\Builder as HttpClientBuilder;
use Ergast\HttpClient\Plugin\ErgastExceptionThrower;
use Ergast\HttpClient\Plugin\ResponseFormat;
use Ergast\Model\Response;
use Ergast\Serializer\Handler\EmptyDateTimeHandler;
use Http\Client\Common\HttpMethodsClient;
use Http\Client\Common\Plugin;
use Http\Discovery\UriFactoryDiscovery;
use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;

/**
 * @author Brieuc Thomas <tbrieuc@gmail.com>
 */
class Client implements ClientInterface
{
    private const BASE_URL = 'https://ergast.com/api';

    private $httpClientBuilder;
    private $serializer;

    public function __construct(
        string $baseUrl = null,
        HttpClientBuilder $httpClientBuilder = null,
        SerializerBuilder $serializerBuilder = null
    ) {
        $this->httpClientBuilder = $builder = $httpClientBuilder ?: new HttpClientBuilder();
        $builder->addPlugin(new Plugin\BaseUriPlugin(UriFactoryDiscovery::find()->createUri($baseUrl ?? self::BASE_URL)));
        $builder->addPlugin(new ResponseFormat('xml'));
        $builder->addPlugin(new ErgastExceptionThrower());

        $serializerBuilder = $serializerBuilder ?? SerializerBuilder::create();
        $serializerBuilder
            ->addMetadataDir(dirname(__DIR__).'/resources/serializer')
            ->configureHandlers(function (HandlerRegistry $registry) {
                $registry->registerSubscribingHandler(new EmptyDateTimeHandler());
            })
        ;

        $this->serializer = $serializerBuilder->build();
    }

    public function sendRequest(string $path): Response
    {
        $xml = (string) $this->getHttpClient()->get($path)->getBody();

        return $this->getSerializer()->deserialize($xml, Response::class, 'xml');
    }

    public function getHttpClient(): HttpMethodsClient
    {
        return $this->getHttpClientBuilder()->getHttpClient();
    }

    public function getSerializer(): SerializerInterface
    {
        return $this->serializer;
    }

    protected function getHttpClientBuilder(): HttpClientBuilder
    {
        return $this->httpClientBuilder;
    }
}
