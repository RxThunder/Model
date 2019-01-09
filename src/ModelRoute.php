<?php

/*
 * This file is part of the Thunder micro CLI framework.
 * (c) Jérémy Marodon <marodon.jeremy@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RxThunder\Model;

use Rx\Observable;
use RxThunder\Core\Router\AbstractRoute;
use RxThunder\Core\Router\AbstractSubject;
use RxThunder\Core\Router\DataModel;

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

        return Observable::of($dataModel)
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

        return (new $fcqn())->build($dataModel->getPayload());
    }

    abstract public function getBuilder(): string;
}
