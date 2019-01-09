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
use RxThunder\Model\Validation\ValidatorInterface;

abstract class ModelValidationRoute extends AbstractRoute
{
    public const VALIDATION_SCHEMA_PATH = 'default.json';

    protected $handler;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct($handler, ValidatorInterface $validator)
    {
        $this->handler = $handler;
        $this->validator = $validator;
    }

    public function __invoke(AbstractSubject $subject)
    {
        $dataModel = $subject->getDataModel();

        return Observable::of($dataModel)
            ->flatMap([$this, 'validate'])
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

    public function validate(DataModel $dataModel): Observable
    {
        $this->validator->validate($dataModel->getPayload(), self::VALIDATION_SCHEMA_PATH);

        return Observable::of($dataModel);
    }

    abstract public function getBuilder(): string;
}
