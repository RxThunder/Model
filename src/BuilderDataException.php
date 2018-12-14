<?php

/*
 * This file is part of the Thunder micro CLI framework.
 * (c) Jérémy Marodon <marodon.jeremy@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Th3Mouk\Thunder\Model;

final class BuilderDataException extends \RuntimeException
{
    public function __construct($data)
    {
        parent::__construct(sprintf('The builder only accepts array or json data, %s given', \gettype($data)));
    }
}
