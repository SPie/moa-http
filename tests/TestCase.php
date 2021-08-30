<?php

namespace Moa\Tests;

use Faker\Factory;
use Faker\Generator;
use Mockery;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    private Generator $faker;

    public function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    protected function getFaker(): Generator
    {
        if (!isset($this->faker)) {
            $this->faker = Factory::create();
        }

        return $this->faker;
    }
}
