<?php

/*
 * This file is part of the Thunder micro CLI framework.
 * (c) Jérémy Marodon <marodon.jeremy@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Th3Mouk\Thunder\Model;

use Rx\Observable;
use Th3Mouk\Thunder\Router\AbstractRoute;
use Th3Mouk\Thunder\Router\AbstractSubject;
use Th3Mouk\Thunder\Router\DataModel;

abstract class ModelRoute extends AbstractRoute
{
    protected $handler;

    public function __construct($handler)
    {
        $this->handler = $handler;
    }

    public function __invoke(AbstractSubject $subject)
    {
        $dataModel = $subject->getDataModel();

        Observable::of($dataModel)
            ->filter([$this, 'validate'])
            ->map([$this, 'modelize'])
            ->flatMap($this->handler)
            ->subscribe(
                null,
                function ($e) use ($subject) {
                    $subject->onError($e);
                },
                function () use ($subject) {
                    $subject->onCompleted();
                }
            );
    }

    public function modelize(DataModel $dataModel)
    {
        $fcqn = $this->getBuilder();

        return (new $fcqn())->build($dataModel->getData());
    }

    abstract public function validate(DataModel $dataModel): bool;

    abstract public function getBuilder(): string;
}
