<?php

declare(strict_types=1);

namespace App\Model\Restaurant\Establishment\Entity;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use DomainException;

final class DishRepository
{
    /**
     * @param EntityRepository<Dish> $repo
     */
    private readonly EntityRepository $repo;

    public function __construct(private readonly EntityManagerInterface $em)
    {
        $this->repo = $this->em->getRepository(Dish::class);
    }

    public function add(Dish $dish): void
    {
        $this->em->persist($dish);
    }

    public function remove(Dish $dish): void
    {
        $this->em->remove($dish);
    }

    public function get(string $id): Dish
    {
        $dish = $this->repo->find($id);

        if (null === $dish) {
            throw new DomainException('Dish is not found.');
        }

        return $dish;
    }

    public function hasByName(string $name): bool
    {
        return $this->repo->createQueryBuilder('d')
            ->select('COUNT(d.id)')
            ->andWhere('d.name = :name')
            ->setParameter(':name', $name)
            ->getQuery()->getSingleScalarResult() > 0;
    }
}
