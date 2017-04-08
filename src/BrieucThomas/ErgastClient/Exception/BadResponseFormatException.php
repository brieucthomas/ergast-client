<?php

/*
 * (c) Brieuc Thomas <tbrieuc@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BrieucThomas\ErgastClient\Exception;

/**
 * Thrown when a response format is not supported.
 *
 * @author Brieuc Thomas <tbrieuc@gmail.com>
 */
class BadResponseFormatException extends \Exception implements ExceptionInterface
{
    public function __construct(string $currentFormat, array $supportedFormats)
    {
        $message = sprintf(
            'Supported response formats are %s, got %s.',
            implode(' or ', $supportedFormats),
            $currentFormat
        );

        parent::__construct($message);
    }
}
