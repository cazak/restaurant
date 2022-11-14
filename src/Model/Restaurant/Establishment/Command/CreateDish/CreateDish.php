<?php

declare(strict_types=1);

namespace App\Model\Restaurant\Establishment\Command\CreateDish;

use App\Model\Infrastructure\Flush;
use App\Model\Restaurant\Establishment\Entity\DishRepository;
use App\Model\Restaurant\Establishment\Factory\DishFactory;

final class CreateDish
{
    public function __construct(
        private readonly DishFactory $factory,
        private readonly DishRepository $repository,
        private readonly Flush $flush,
    ) {
    }

    /**
     * @throws DishAlreadyExistException
     */
    public function __invoke(CreateDishCommand $command): string
    {
        if ($this->repository->hasByName($command->name)) {
            throw new DishAlreadyExistException();
        }

        $dish = $this->factory->create($command->name, $command->price);

        $this->repository->add($dish);
        ($this->flush)();

        return $dish->getId()->getValue();
    }
}
