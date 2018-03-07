<?php

/*
 * (c) Brieuc Thomas <tbrieuc@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ergast\Serializer\Handler;

use JMS\Serializer\Handler\DateHandler;
use JMS\Serializer\XmlDeserializationVisitor;

/**
 * @author Brieuc Thomas <tbrieuc@gmail.com>
 */
class EmptyDateTimeHandler extends DateHandler
{
    /**
     * {@inheritdoc}
     */
    public function deserializeDateTimeFromXml(XmlDeserializationVisitor $visitor, $data, array $type)
    {
        if ('' === (string) $data) {
            return null;
        }

        return parent::deserializeDateTimeFromXml($visitor, $data, $type);
    }
}
