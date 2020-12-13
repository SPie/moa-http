<?php

namespace Moa\Tests;

/**
 * Trait Reflection
 *
 * @package Moa\Tests
 */
trait Reflection
{
    /**
     * @param mixed $object
     *
     * @return \ReflectionObject
     */
    private function getReflectionObject($object): \ReflectionObject
    {
        return new \ReflectionObject($object);
    }

    /**
     * @param mixed  $object
     * @param string $propertyName
     * @param mixed  $propertyValue
     *
     * @return mixed
     */
    private function setReflectionProperty($object, string $propertyName, $propertyValue)
    {
        $property = $this->getReflectionObject($object)->getProperty($propertyName);

        $property->setAccessible(true);
        $property->setValue($object, $propertyValue);

        return $object;
    }
}
