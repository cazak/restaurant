<?php

declare(strict_types=1);

namespace App\Model\Restaurant\Order\Entity;

use App\Model\Shared\Entity\ValueObject\Id;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: '`restaurant_order_item`')]
class OrderItem
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid_id')]
    private readonly Id $id;

    #[ORM\ManyToOne(inversedBy: 'items'), ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private readonly Order $order;

    #[ORM\Column(type: 'float')]
    private readonly float $price;

    #[ORM\Column(type: 'string')]
    private readonly string $title;

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $createdAt;

    public function __construct(Id $id, DateTimeImmutable $createdAt, Order $order, float $price, string $title)
    {
        $this->id = $id;
        $this->createdAt = $createdAt;
        $this->order = $order;
        $this->price = $price;
        $this->title = $title;
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getOrder(): Order
    {
        return $this->order;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getTitle(): string
    {
        return $this->title;
    }
}
