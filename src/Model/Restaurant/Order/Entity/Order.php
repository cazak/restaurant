<?php

declare(strict_types=1);

namespace App\Model\Restaurant\Order\Entity;

use App\Model\Restaurant\Order\Entity\Event\OrderPaid;
use App\Model\Shared\Entity\AggregateRoot;
use App\Model\Shared\Entity\EventsTrait;
use App\Model\Shared\Entity\ValueObject\Id;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: '`restaurant_order`')]
class Order implements AggregateRoot
{
    use EventsTrait;

    #[ORM\Id]
    #[ORM\Column(type: 'uuid_id')]
    private readonly Id $id;

    #[ORM\Column(type: 'order_customer_uuid_id')]
    private readonly CustomerId $customerId;

    #[ORM\Column(type: 'datetime_immutable')]
    private readonly DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?DateTimeImmutable $paidAt = null;

    #[ORM\Column(type: 'restaurant_order_status')]
    private OrderStatus $status;

    #[ORM\Column(type: 'float')]
    private float $price;

    /**
     * @var Collection<int, OrderItem>
     */
    #[ORM\OneToMany(mappedBy: 'order', targetEntity: OrderItem::class, cascade: ['all'], orphanRemoval: true)]
    private Collection $items;

    public function __construct(Id $id, CustomerId $customerId, DateTimeImmutable $createdAt)
    {
        $this->id = $id;
        $this->customerId = $customerId;
        $this->createdAt = $createdAt;
        $this->status = OrderStatus::new();
        $this->price = 0;
        $this->items = new ArrayCollection();
    }

    /**
     * @throws OrderAlreadyPaidException
     */
    public function pay(): void
    {
        if ($this->status->isPaid()) {
            throw new OrderAlreadyPaidException();
        }

        $this->paidAt = new DateTimeImmutable();
        $this->status = OrderStatus::paid();
        $this->recordEvent(new OrderPaid($this->id));
    }

    /**
     * @throws AddItemToPaidOrderException
     */
    public function modify(OrderItem $orderItem): void
    {
        if ($this->status->isPaid()) {
            throw new AddItemToPaidOrderException();
        }

        $this->price += $orderItem->getPrice();
        foreach ($this->items as $item) {
            if ($item->isEqual($orderItem)) {
                $item->add($item->getQuantity() + 1);

                return;
            }
        }
        $this->items->add($orderItem);
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getCustomerId(): CustomerId
    {
        return $this->customerId;
    }

    public function getStatus(): OrderStatus
    {
        return $this->status;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getPaidAt(): DateTimeImmutable
    {
        return $this->paidAt;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getItems(): Collection
    {
        return $this->items;
    }
}
