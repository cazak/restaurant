<?php

declare(strict_types=1);

namespace App\Model\Restaurant\Establishment\Entity;

use App\Model\Restaurant\Shared\Entity\ValueObject\Id;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: '`restaurant_dish`')]
class Dish
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid_id')]
    private readonly Id $id;

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'float')]
    private float $price;

    #[ORM\Column(type: 'string', unique: true)]
    private string $name;

    public function __construct(Id $id, DateTimeImmutable $createdAt, float $price, string $name)
    {
        $this->id = $id;
        $this->createdAt = $createdAt;
        $this->price = $price;
        $this->name = $name;
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
