<?php

/*
 * This file is part of the Thunder micro CLI framework.
 * (c) Jérémy Marodon <marodon.jeremy@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RxThunder\Model;

use RxThunder\Core\Router\Payload;

abstract class Builder implements BuilderInterface
{
    public function build(Payload $payload)
    {
        if (!\in_array($payload->getDataType(), ['string', 'array'])) {
            throw new BuilderDataException($payload->getDataType());
        }

        if ('string' === $payload->getDataType()) {
            if (!\is_array($data = json_decode($payload->getStringData(), true))) {
                throw new BuilderDataException($payload->getDataType());
            }
        } else {
            $data = $payload->getArrayData();
        }

        $this->validate($data);

        return $this->map($data);
    }

    /**
     * Override this method to validate data with custom logic
     * This method must throw exceptions if fail.
     */
    public function validate(array $data): void
    {
    }

    abstract public function map(array $data);
}
