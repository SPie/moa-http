<?php

namespace Moa\Tests;

use Faker\Factory;
use Faker\Generator;
use Mockery;
use PHPUnit\Framework\TestCase as BaseTestCase;

/**
 * Class TestCase
 *
 * @package Moa\Tests
 */
abstract class TestCase extends BaseTestCase
{
    /**
     * @var Generator
     */
    private Generator $faker;

    /**
     * @return void
     */
    public function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    /**
     * @return Generator
     */
    protected function getFaker(): Generator
    {
        if (!isset($this->faker)) {
            $this->faker = Factory::create();
        }

        return $this->faker;
    }
}
