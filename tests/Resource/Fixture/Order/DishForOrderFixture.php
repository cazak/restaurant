<?php

declare(strict_types=1);

namespace App\Tests\Resource\Fixture\Order;

use App\Model\Restaurant\Establishment\Factory\DishFactory;
use App\Tests\Tools\FakerTools;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class DishForOrderFixture extends Fixture
{
    use FakerTools;

    public const REFERENCE = 'dish';
    public const PRICE = 100;

    public function __construct(private readonly DishFactory $factory)
    {
    }

    public function load(ObjectManager $manager)
    {
        $dish = $this->factory->create(
            $this->getFaker()->title(),
            self::PRICE
        );

        $manager->persist($dish);
        $manager->flush();

        $this->addReference(self::REFERENCE, $dish);
    }
}
