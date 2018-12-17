<?php

/*
 * This file is part of the Thunder micro CLI framework.
 * (c) Jérémy Marodon <marodon.jeremy@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Th3Mouk\Thunder\Model;

abstract class Builder implements BuilderInterface
{
    /**
     * @var array
     */
    protected $data;

    public function build($data)
    {
        if (!\is_array($data)) {
            if (!\is_array($data = json_decode($data, true))) {
                throw new BuilderDataException($data);
            }
        }
        $this->data = $data;

        $this->validate();
        return $this->map();
    }

    /**
     * Override this method to validate data with custom logic
     * This method must throw exceptions if fail
     */
    public function validate(): void
    {
    }

    abstract public function map();
}
