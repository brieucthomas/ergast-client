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
class Driver
{
    private $id;
    private $permanentNumber;
    private $code;
    private $givenName;
    private $familyName;
    private $dateOfBirth;
    private $nationality;
    private $url;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getPermanentNumber(): ?int
    {
        return $this->permanentNumber;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function getGivenName(): ?string
    {
        return $this->givenName;
    }

    public function getFamilyName(): ?string
    {
        return $this->familyName;
    }

    public function getDateOfBirth(): ?\DateTime
    {
        return $this->dateOfBirth;
    }

    public function getNationality(): ?string
    {
        return $this->nationality;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }
}
