<?php

namespace Moa\Tests;

trait Reflection
{
    private function getReflectionObject($object): \ReflectionObject
    {
        return new \ReflectionObject($object);
    }

    /**
     * @param mixed  $object
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

    /**
     * @param mixed  $object
     *
     * @return mixed
     */
    private function getReflectionProperty($object, string $propertyName)
    {
        $property = $this->getReflectionObject($object)->getProperty($propertyName);

        $property->setAccessible(true);

        return $property->getValue($object);
    }
}
