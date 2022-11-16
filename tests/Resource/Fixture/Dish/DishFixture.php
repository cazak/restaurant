<?php

declare(strict_types=1);

namespace App\Tests\Resource\Fixture\Dish;

use App\Model\Restaurant\Establishment\Factory\DishFactory;
use App\Tests\Tools\FakerTools;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class DishFixture extends Fixture
{
    use FakerTools;

    private const DISH_COUNT = 20;
    private const MIN_PRICE = 50;
    private const MAX_PRICE = 1000;

    public function __construct(private readonly DishFactory $factory)
    {
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < self::DISH_COUNT; ++$i) {
            $dish = $this->factory->create(
                $this->getFaker()->title().$i,
                random_int(self::MIN_PRICE, self::MAX_PRICE)
            );

            $manager->persist($dish);
        }

        $manager->flush();
    }
}
