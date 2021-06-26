<?php

namespace App\DataFixtures;

use Faker;
use Faker\Factory;
use Faker\Generator as FakerGenerator;
use Nelmio\Alice\Loader\NativeLoader;

class ContactNativeLoader extends NativeLoader
{
    protected function createFakerGenerator(): FakerGenerator
    {
        $generator = Factory::create('fr_FR');

        return $generator;
    }

}