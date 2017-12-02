<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Caster extends Model
{
    /**
     * Casts a generic \stdClass object into a Laravel Model
     *
     * For more info: https://stackoverflow.com/questions/3243900/convert-cast-an-stdclass-object-to-another-class
     *
     * @param  string|object $destination   The model to be casted into
     * @param  object $sourceObject         The stdClass that is to be cast
     * @return object                       The sourceObject cast as a Laravel model
     */
    public static function cast($destination, $sourceObject)
    {
        if (is_string($destination)) {
            $destination = new $destination();
        }
        $sourceReflection = new \ReflectionObject($sourceObject);
        $destinationReflection = new \ReflectionObject($destination);
        $sourceProperties = $sourceReflection->getProperties();
        foreach ($sourceProperties as $sourceProperty) {
            $sourceProperty->setAccessible(true);
            $name = $sourceProperty->getName();
            $value = $sourceProperty->getValue($sourceObject);
            if ($destinationReflection->hasProperty($name)) {
                $propDest = $destinationReflection->getProperty($name);
                $propDest->setAccessible(true);
                $propDest->setValue($destination,$value);
            } else {
                $destination->$name = $value;
            }
        }
        return $destination;
    }
}
