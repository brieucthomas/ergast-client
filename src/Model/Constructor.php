<?php

/*
 * (c) Brieuc Thomas <tbrieuc@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ergast\Model;

/**
 * @author Brieuc Thomas <brieuc.thomas@orange.com>
 */
class Constructor
{
    private $id;
    private $name;
    private $nationality;
    private $url;

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getNationality(): string
    {
        return $this->nationality;
    }

    public function getUrl(): string
    {
        return $this->url;
    }
}
