<?php

namespace Wnx\LaravelStats;

use Illuminate\Support\Collection;
use Wnx\LaravelStats\Classifiers\Classifier;
use ReflectionClass as NativeReflectionClass;

class ReflectionClass extends NativeReflectionClass
{
    public function isVendorProvided()
    {
        return str_contains($this->getFileName(), '/vendor/');
    }

    public function getLaravelComponentName()
    {
        return (new Classifier())->classify($this);
    }

    public function isLaravelComponent()
    {
        return (bool) $this->getLaravelComponentName();
    }

    /**
     * Determine whether the class uses the given trait.
     *
     * @param  string $name
     * @return bool
     */
    public function usesTrait($name)
    {
        return collect($this->getTraits())
            ->contains(function ($trait) use ($name) {
                return $trait->name == $name;
            });
    }

    /**
     * Return a collection of methods defined on the given class.
     * This ignores methods defined in parent class, traits etc.
     *
     * @return Collection
     */
    public function getDefinedMethods() : Collection
    {
        return collect($this->getMethods())
            ->filter(function ($method) {
                return $method->getFileName() == $this->getFileName();
            });
    }
}
