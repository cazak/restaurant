<?php

declare(strict_types=1);

namespace App\Model\Restaurant\Order\Entity;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use DomainException;

final class OrderItemRepository
{
    /**
     * @param EntityRepository<OrderItem> $repo
     */
    private readonly EntityRepository $repo;

    public function __construct(private readonly EntityManagerInterface $em)
    {
        $this->repo = $this->em->getRepository(OrderItem::class);
    }

    public function add(OrderItem $dish): void
    {
        $this->em->persist($dish);
    }

    public function remove(OrderItem $dish): void
    {
        $this->em->remove($dish);
    }

    public function find(string $id): ?OrderItem
    {
        return $this->repo->findOneBy(['id' => $id]);
    }

    public function get(string $id): OrderItem
    {
        $dish = $this->repo->find($id);

        if (null === $dish) {
            throw new DomainException('Order is not found.');
        }

        return $dish;
    }
}
