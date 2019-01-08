<?php

/*
 * This file is part of the Thunder micro CLI framework.
 * (c) Jérémy Marodon <marodon.jeremy@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RxThunder\Model;

use RxThunder\Core\Router\Payload;

interface BuilderInterface
{
    public function build(Payload $payload);

    public function validate(array $data): void;

    public function map(array $data);
}
