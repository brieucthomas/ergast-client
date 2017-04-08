<?php

/*
 * (c) Brieuc Thomas <tbrieuc@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BrieucThomas\ErgastClient;

use BrieucThomas\ErgastClient\Exception\BadResponseFormatException;
use BrieucThomas\ErgastClient\Model\Response;
use GuzzleHttp\ClientInterface;
use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\SerializerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @author Brieuc Thomas <tbrieuc@gmail.com>
 */
class ErgastClient implements ErgastClientInterface
{
    private $httpClient;
    private $serializer;
    private $supportedMimeTypes = [
        'application/xml' => 'xml'
    ];

    public function __construct(ClientInterface $httpClient, SerializerInterface $serializer)
    {
        $this->httpClient = $httpClient;
        $this->serializer = $serializer;
    }

    public function execute(RequestInterface $request) : Response
    {
        $response = $this->httpClient->send($request);
        $responseContent = $response->getBody()->getContents();
        $responseFormat = $this->getResponseFormat($response);

        return $this->serializer->deserialize($responseContent, Response::class, $responseFormat);
    }

    private function getResponseFormat(ResponseInterface $response) : string
    {
        $mimeType = $response->getHeaderLine('Content-Type');

        if (false !== $pos = strpos($mimeType, ';')) {
            $mimeType = substr($mimeType, 0, $pos);
        }

        if (isset($this->supportedMimeTypes[$mimeType])) {
            return $this->supportedMimeTypes[$mimeType];
        }

        throw new BadResponseFormatException($mimeType, array_keys($this->supportedMimeTypes));
    }
}
