<?php

declare(strict_types=1);

namespace App\Model\Restaurant\Order\Entity;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use DomainException;

final class OrderRepository
{
    /**
     * @param EntityRepository<Order> $repo
     */
    private readonly EntityRepository $repo;

    public function __construct(private readonly EntityManagerInterface $em)
    {
        $this->repo = $this->em->getRepository(Order::class);
    }

    public function add(Order $dish): void
    {
        $this->em->persist($dish);
    }

    public function remove(Order $dish): void
    {
        $this->em->remove($dish);
    }

    public function get(string $id): Order
    {
        $dish = $this->repo->find($id);

        if (null === $dish) {
            throw new DomainException('Order is not found.');
        }

        return $dish;
    }

    public function findByCustomer(string $customerId): ?Order
    {
        return $this->repo->findOneBy(['owner' => $customerId]);
    }
}
