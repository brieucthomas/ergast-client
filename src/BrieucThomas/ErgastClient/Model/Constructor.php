<?php

/*
 * (c) Brieuc Thomas <tbrieuc@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BrieucThomas\ErgastClient\Model;

/**
 * @author Brieuc Thomas <brieuc.thomas@orange.com>
 */
class Constructor
{
    private $id;
    private $name;
    private $nationality;
    private $url;

    /**
     * Returns the constructor slug.
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Returns the constructor name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Returns the constructor nationality.
     *
     * @return string
     */
    public function getNationality(): string
    {
        return $this->nationality;
    }

    /**
     * Returns the constructor Wikipedia page url.
     *
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }
}
