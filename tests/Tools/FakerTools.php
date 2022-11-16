<?php

declare(strict_types=1);

namespace App\Tests\Tools;

use Faker\Factory;
use Faker\Generator;

trait FakerTools
{
    private ?Generator $generator = null;

    public function getFaker(): Generator
    {
        if (null === $this->generator) {
            $this->generator = Factory::create();
        }

        return $this->generator;
    }
}
