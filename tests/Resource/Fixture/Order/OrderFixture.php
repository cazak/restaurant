<?php

declare(strict_types=1);

namespace App\Tests\Resource\Fixture\Order;

use App\Model\Restaurant\Order\Factory\OrderFactory;
use App\Model\Shared\Entity\ValueObject\Id;
use App\Tests\Tools\FakerTools;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class OrderFixture extends Fixture
{
    use FakerTools;

    public const REFERENCE = 'order';

    public function __construct(private readonly OrderFactory $factory)
    {
    }

    public function load(ObjectManager $manager)
    {
        $order = $this->factory->create((string) Id::next());

        $manager->persist($order);
        $manager->flush();

        $this->addReference(self::REFERENCE, $order);
    }
}
