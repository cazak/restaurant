<?php

declare(strict_types=1);

namespace App\Tests\Resource\DevFixture\Order;

use App\Model\Restaurant\Order\Entity\DishId;
use App\Model\Restaurant\Order\Entity\Order;
use App\Model\Restaurant\Order\Entity\OrderItem;
use App\Model\Restaurant\Order\Factory\OrderFactory;
use App\Model\Shared\Entity\ValueObject\Id;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ObjectManager;

final class OrderFixture extends Fixture implements DependentFixtureInterface
{
    private const ORDER_COUNT = 1000;
    private const MIN_ORDER_ITEMS_COUNT = 1;
    private const MAX_ORDER_ITEMS_COUNT = 15;
    private const MIN_ORDER_ITEMS_QUANTITY = 1;
    private const MAX_ORDER_ITEMS_QUANTITY = 8;

    public function __construct(
        private readonly OrderFactory $orderFactory,
        private readonly Connection $connection,
    ) {
    }

    public function load(ObjectManager $manager)
    {
        $dishes = $this->getDishes();
        for ($i = 0; $i < self::ORDER_COUNT; ++$i) {
            $order = $this->orderFactory->create((string) Id::next());
            $manager->persist($order);

            $this->createOrderItems($order, $manager, $dishes);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            DishFixture::class,
        ];
    }

    private function createOrderItems(Order $order, ObjectManager $manager, array $dishes): void
    {
        $myDishes = $dishes;
        $orderItemsCount = random_int(self::MIN_ORDER_ITEMS_COUNT, self::MAX_ORDER_ITEMS_COUNT);
        for ($i = 0; $i < $orderItemsCount; ++$i) {
            do {
                $key = array_rand($dishes);
            } while (!array_key_exists($key, $myDishes));
            $orderItem = new OrderItem(
                Id::next(),
                new DishId($myDishes[$key]['id']),
                new DateTimeImmutable(),
                $order,
                (float) $myDishes[$key]['price'],
                $myDishes[$key]['name'],
                random_int(self::MIN_ORDER_ITEMS_QUANTITY, self::MAX_ORDER_ITEMS_QUANTITY)
            );
            unset($myDishes[$key]);
            $manager->persist($orderItem);
        }
    }

    private function getDishes(): array
    {
        return $this->connection->createQueryBuilder()
            ->select(['id', 'price', 'name'])
            ->from('restaurant_dish')
            ->executeQuery()
            ->fetchAllAssociative();
    }
}
