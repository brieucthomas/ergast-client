<?php

/*
 * (c) Brieuc Thomas <tbrieuc@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ergast\Tests;

use Ergast\Client;
use Ergast\HttpClient\Builder as HttpClientBuilder;
use Ergast\Model\Driver;
use Ergast\Model\ResponseInterface;
use Ergast\Model\Season;
use Http\Mock\Client as FakeHttpClient;
use PHPUnit\Framework\TestCase as BaseTestCase;
use Psr\Http\Message\ResponseInterface as HttpResponseInterface;
use Psr\Http\Message\StreamInterface;

class TestCase extends BaseTestCase
{
    protected function createClient(string $expectedResponseBody): Client
    {
        $stream = $this->getMockBuilder(StreamInterface::class)->getMock();
        $stream->expects($this->any())->method('__toString')->willReturn($expectedResponseBody);

        $response = $this->getMockBuilder(HttpResponseInterface::class)->getMock();
        $response->expects($this->any())->method('getBody')->willReturn($stream);

        $httpClient = new FakeHttpClient();
        $httpClient->addResponse($response);

        return new Client(null, new HttpClientBuilder($httpClient));
    }

    protected function assertDate(?\DateTime $date, string $value)
    {
        $this->assertInstanceOf(\DateTime::class, $date);
        $this->assertSame($value, $date->format(\DateTime::ATOM));
    }

    protected function assertResponseMetadata(ResponseInterface $metadata, $url, $series, $limit, $offset, $total)
    {
        $this->assertSame($url, $metadata->getUrl());
        $this->assertSame($series, $metadata->getSeries());
        $this->assertSame($limit, $metadata->getLimit());
        $this->assertSame($offset, $metadata->getOffset());
        $this->assertSame($total, $metadata->getTotal());
    }

    protected function assertIsDanielRicciardo(Driver $driver)
    {
        $this->assertSame('ricciardo', $driver->getId());
        $this->assertSame(3, $driver->getPermanentNumber());
        $this->assertSame('RIC', $driver->getCode());
        $this->assertSame('Daniel', $driver->getGivenName());
        $this->assertSame('Ricciardo', $driver->getFamilyName());
        $this->assertSame('Australian', $driver->getNationality());
        $this->assertSame('http://en.wikipedia.org/wiki/Daniel_Ricciardo', $driver->getUrl());
        $this->assertDate($driver->getDateOfBirth(), '1989-07-01T00:00:00+00:00');
    }

    protected function assertIsNicoRosberg(Driver $driver)
    {
        $this->assertSame('rosberg', $driver->getId());
        $this->assertSame(6, $driver->getPermanentNumber());
        $this->assertSame('ROS', $driver->getCode());
        $this->assertSame('Nico', $driver->getGivenName());
        $this->assertSame('Rosberg', $driver->getFamilyName());
        $this->assertSame('German', $driver->getNationality());
        $this->assertSame('http://en.wikipedia.org/wiki/Nico_Rosberg', $driver->getUrl());
        $this->assertDate($driver->getDateOfBirth(), '1985-06-27T00:00:00+00:00');
    }

    protected function assertIsAyrtonSenna(Driver $driver)
    {
        $this->assertSame('senna', $driver->getId());
        $this->assertSame('Brazilian', $driver->getNationality());
        $this->assertSame('Senna', $driver->getFamilyName());
        $this->assertSame('Ayrton', $driver->getGivenName());
        $this->assertNull($driver->getCode());
        $this->assertNull($driver->getPermanentNumber());
        $this->assertSame('http://en.wikipedia.org/wiki/Ayrton_Senna', $driver->getUrl());
        $this->assertDate($driver->getDateOfBirth(), '1960-03-21T00:00:00+00:00');
    }

    protected function assertIsRayReed(Driver $driver)
    {
        $this->assertSame('reed', $driver->getId());
        $this->assertSame('South African', $driver->getNationality());
        $this->assertSame('Reed', $driver->getFamilyName());
        $this->assertSame('Ray', $driver->getGivenName());
        $this->assertNull($driver->getCode());
        $this->assertNull($driver->getPermanentNumber());
        $this->assertSame('http://en.wikipedia.org/wiki/Ray_Reed', $driver->getUrl());
        $this->assertNull($driver->getDateOfBirth());
    }

    protected function assertIsLewisHamilton(Driver $driver)
    {
        $this->assertSame('hamilton', $driver->getId());
        $this->assertSame('British', $driver->getNationality());
        $this->assertSame('Hamilton', $driver->getFamilyName());
        $this->assertSame('Lewis', $driver->getGivenName());
        $this->assertSame('HAM', $driver->getCode());
        $this->assertSame(44, $driver->getPermanentNumber());
        $this->assertSame('http://en.wikipedia.org/wiki/Lewis_Hamilton', $driver->getUrl());
        $this->assertDate($driver->getDateOfBirth(), '1985-01-07T00:00:00+00:00');
    }

    protected function assertSeason(Season $season, $year, $url)
    {
        $this->assertSame($year, $season->getYear());
        $this->assertSame($url, $season->getUrl());
    }

    private function assertBirthDate(?\DateTime $birthDate, string $value)
    {
        $this->assertInstanceOf(\DateTime::class, $birthDate);
        $this->assertSame($value, $birthDate->format('Y-m-d'));
    }
}
