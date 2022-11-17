<?php

declare(strict_types=1);

namespace App\Tests\Resource\DevFixture\Order;

use App\Model\Restaurant\Establishment\Factory\DishFactory;
use App\Tests\Tools\FakerTools;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class DishFixture extends Fixture
{
    use FakerTools;

    private const COUNT = 150;
    private const MIN_PRICE = 20;
    private const MAX_PRICE = 120;

    public function __construct(private readonly DishFactory $factory)
    {
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < self::COUNT; ++$i) {
            $dish = $this->factory->create(
                $this->getFaker()->uuid(),
                random_int(self::MIN_PRICE, self::MAX_PRICE)
            );

            $manager->persist($dish);
        }

        $manager->flush();
    }
}
