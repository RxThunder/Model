<?php

/*
 * This file is part of the Thunder micro CLI framework.
 * (c) Jérémy Marodon <marodon.jeremy@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RxThunder\Model\Validation;

use RxThunder\Core\Router\Payload;

interface ValidatorInterface
{
    public function validate(Payload $payload, string $schema): void;
}
